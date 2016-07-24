<?php
 
defined('BASEPATH') OR exit('No direct script access allowed');

 
class CI_Security {

	 
	public $filename_bad_chars =	array(
		'../', '<!--', '-->', '<', '>',
		"'", '"', '&', '$', '#',
		'{', '}', '[', ']', '=',
		';', '?', '%20', '%22',
		'%3c',		// <
		'%253c',	// <
		'%3e',		// >
		'%0e',		// >
		'%28',		// (
		'%29',		// )
		'%2528',	// (
		'%26',		// &
		'%24',		// $
		'%3f',		// ?
		'%3b',		// ;
		'%3d'		// =
	);

	 
	public $charset = 'UTF-8';

	 
	protected $_xss_hash;

	 
	protected $_csrf_hash;

	 
	protected $_csrf_expire =	7200;

	 
	protected $_csrf_token_name =	'ci_csrf_token';

	 
	protected $_csrf_cookie_name =	'ci_csrf_token';

	 
	protected $_never_allowed_str =	array(
		'document.cookie'	=> '[removed]',
		'document.write'	=> '[removed]',
		'.parentNode'		=> '[removed]',
		'.innerHTML'		=> '[removed]',
		'-moz-binding'		=> '[removed]',
		'<!--'				=> '&lt;!--',
		'-->'				=> '--&gt;',
		'<![CDATA['			=> '&lt;![CDATA[',
		'<comment>'			=> '&lt;comment&gt;'
	);

	 
	protected $_never_allowed_regex = array(
		'javascript\s*:',
		'(document|(document\.)?window)\.(location|on\w*)',
		'expression\s*(\(|&\#40;)', // CSS and IE
		'vbscript\s*:', // IE, surprise!
		'wscript\s*:', // IE
		'jscript\s*:', // IE
		'vbs\s*:', // IE
		'Redirect\s+30\d',
		"([\"'])?data\s*:[^\\1]*?base64[^\\1]*?,[^\\1]*?\\1?"
	);

	 
	public function __construct()
	{
 
		if (config_item('csrf_protection'))
		{
 
			foreach (array('csrf_expire', 'csrf_token_name', 'csrf_cookie_name') as $key)
			{
				if (NULL !== ($val = config_item($key)))
				{
					$this->{'_'.$key} = $val;
				}
			}
 
			if ($cookie_prefix = config_item('cookie_prefix'))
			{
				$this->_csrf_cookie_name = $cookie_prefix.$this->_csrf_cookie_name;
			}
 
			$this->_csrf_set_hash();
		}

		$this->charset = strtoupper(config_item('charset'));

		log_message('info', 'Security Class Initialized');
	}
 
	public function csrf_verify()
	{
 
		if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST')
		{
			return $this->csrf_set_cookie();
		}
 
		if ($exclude_uris = config_item('csrf_exclude_uris'))
		{
			$uri = load_class('URI', 'core');
			foreach ($exclude_uris as $excluded)
			{
				if (preg_match('#^'.$excluded.'$#i'.(UTF8_ENABLED ? 'u' : ''), $uri->uri_string()))
				{
					return $this;
				}
			}
		}
 
		if ( ! isset($_POST[$this->_csrf_token_name], $_COOKIE[$this->_csrf_cookie_name])
			OR $_POST[$this->_csrf_token_name] !== $_COOKIE[$this->_csrf_cookie_name]) // Do the tokens match?
		{
			$this->csrf_show_error();
		}
 
		unset($_POST[$this->_csrf_token_name]);
 
		if (config_item('csrf_regenerate'))
		{
 
			unset($_COOKIE[$this->_csrf_cookie_name]);
			$this->_csrf_hash = NULL;
		}

		$this->_csrf_set_hash();
		$this->csrf_set_cookie();

		log_message('info', 'CSRF token verified');
		return $this;
	}
 
	public function csrf_set_cookie()
	{
		$expire = time() + $this->_csrf_expire;
		$secure_cookie = (bool) config_item('cookie_secure');

		if ($secure_cookie && ! is_https())
		{
			return FALSE;
		}

		setcookie(
			$this->_csrf_cookie_name,
			$this->_csrf_hash,
			$expire,
			config_item('cookie_path'),
			config_item('cookie_domain'),
			$secure_cookie,
			config_item('cookie_httponly')
		);
		log_message('info', 'CSRF cookie sent');

		return $this;
	}
 
	public function csrf_show_error()
	{
		show_error('The action you have requested is not allowed.', 403);
	}
 
	public function get_csrf_hash()
	{
		return $this->_csrf_hash;
	}
 
	public function get_csrf_token_name()
	{
		return $this->_csrf_token_name;
	}
 
	public function xss_clean($str, $is_image = FALSE)
	{
 
		if (is_array($str))
		{
			while (list($key) = each($str))
			{
				$str[$key] = $this->xss_clean($str[$key]);
			}

			return $str;
		}
 
		$str = remove_invisible_characters($str);

		 
		do
		{
			$str = rawurldecode($str);
		}
		while (preg_match('/%[0-9a-f]{2,}/i', $str));

		 
		$str = preg_replace_callback("/[^a-z0-9>]+[a-z0-9]+=([\'\"]).*?\\1/si", array($this, '_convert_attribute'), $str);
		$str = preg_replace_callback('/<\w+.*/si', array($this, '_decode_entity'), $str);
 
		$str = remove_invisible_characters($str);

		 
		$str = str_replace("\t", ' ', $str);
 
		$converted_string = $str;
 
		$str = $this->_do_never_allowed($str);

		 
		if ($is_image === TRUE)
		{
 
 
 
			$str = preg_replace('/<\?(php)/i', '&lt;?\\1', $str);
		}
		else
		{
			$str = str_replace(array('<?', '?'.'>'), array('&lt;?', '?&gt;'), $str);
		}

		 
		$words = array(
			'javascript', 'expression', 'vbscript', 'jscript', 'wscript',
			'vbs', 'script', 'base64', 'applet', 'alert', 'document',
			'write', 'cookie', 'window', 'confirm', 'prompt', 'eval'
		);

		foreach ($words as $word)
		{
			$word = implode('\s*', str_split($word)).'\s*';
 
 
			$str = preg_replace_callback('#('.substr($word, 0, -3).')(\W)#is', array($this, '_compact_exploded_words'), $str);
		}

		 
		do
		{
			$original = $str;

			if (preg_match('/<a/i', $str))
			{
				$str = preg_replace_callback('#<a[^a-z0-9>]+([^>]*?)(?:>|$)#si', array($this, '_js_link_removal'), $str);
			}

			if (preg_match('/<img/i', $str))
			{
				$str = preg_replace_callback('#<img[^a-z0-9]+([^>]*?)(?:\s?/?>|$)#si', array($this, '_js_img_removal'), $str);
			}

			if (preg_match('/script|xss/i', $str))
			{
				$str = preg_replace('#</*(?:script|xss).*?>#si', '[removed]', $str);
			}
		}
		while ($original !== $str);
		unset($original);

		$pattern = '#'
			.'<((?<slash>/*\s*)(?<tagName>[a-z0-9]+)(?=[^a-z0-9]|$)' // tag start and name, followed by a non-tag character
			.'[^\s\042\047a-z0-9>/=]*' // a valid attribute character immediately after the tag would count as a separator
			// optional attributes
			.'(?<attributes>(?:[\s\042\047/=]*' // non-attribute characters, excluding > (tag close) for obvious reasons
			.'[^\s\042\047>/=]+' // attribute characters
			// optional attribute-value
				.'(?:\s*=' // attribute-value separator
					.'(?:[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*))' // single, double or non-quoted value
				.')?' // end optional attribute-value group
			.')*)' // end optional attributes group
			.'[^>]*)(?<closeTag>\>)?#isS';

		// Note: It would be nice to optimize this for speed, BUT
		//       only matching the naughty elements here results in
		//       false positives and in turn - vulnerabilities!
		do
		{
			$old_str = $str;
			$str = preg_replace_callback($pattern, array($this, '_sanitize_naughty_html'), $str);
		}
		while ($old_str !== $str);
		unset($old_str);

		
		$str = preg_replace(
			'#(alert|prompt|confirm|cmd|passthru|eval|exec|expression|system|fopen|fsockopen|file|file_get_contents|readfile|unlink)(\s*)\((.*?)\)#si',
			'\\1\\2&#40;\\3&#41;',
			$str
		);
 
 
 
		$str = $this->_do_never_allowed($str);

		 
		if ($is_image === TRUE)
		{
			return ($str === $converted_string);
		}

		return $str;
	}
 
	public function xss_hash()
	{
		if ($this->_xss_hash === NULL)
		{
			$rand = $this->get_random_bytes(16);
			$this->_xss_hash = ($rand === FALSE)
				? md5(uniqid(mt_rand(), TRUE))
				: bin2hex($rand);
		}

		return $this->_xss_hash;
	}
 
	public function get_random_bytes($length)
	{
		if (empty($length) OR ! ctype_digit((string) $length))
		{
			return FALSE;
		}
 
		if (defined('MCRYPT_DEV_URANDOM') && ($output = mcrypt_create_iv($length, MCRYPT_DEV_URANDOM)) !== FALSE)
		{
			return $output;
		}


		if (is_readable('/dev/urandom') && ($fp = fopen('/dev/urandom', 'rb')) !== FALSE)
		{
 
			is_php('5.4') && stream_set_chunk_size($fp, $length);
			$output = fread($fp, $length);
			fclose($fp);
			if ($output !== FALSE)
			{
				return $output;
			}
		}

		if (function_exists('openssl_random_pseudo_bytes'))
		{
			return openssl_random_pseudo_bytes($length);
		}

		return FALSE;
	}
 
	public function entity_decode($str, $charset = NULL)
	{
		if (strpos($str, '&') === FALSE)
		{
			return $str;
		}

		static $_entities;

		isset($charset) OR $charset = $this->charset;
		$flag = is_php('5.4')
			? ENT_COMPAT | ENT_HTML5
			: ENT_COMPAT;

		do
		{
			$str_compare = $str;
 
			if (preg_match_all('/&[a-z]{2,}(?![a-z;])/i', $str, $matches))
			{
				if ( ! isset($_entities))
				{
					$_entities = array_map(
						'strtolower',
						is_php('5.3.4')
							? get_html_translation_table(HTML_ENTITIES, $flag, $charset)
							: get_html_translation_table(HTML_ENTITIES, $flag)
					);
 
 
					if ($flag === ENT_COMPAT)
					{
						$_entities[':'] = '&colon;';
						$_entities['('] = '&lpar;';
						$_entities[')'] = '&rpar;';
						$_entities["\n"] = '&newline;';
						$_entities["\t"] = '&tab;';
					}
				}

				$replace = array();
				$matches = array_unique(array_map('strtolower', $matches[0]));
				foreach ($matches as &$match)
				{
					if (($char = array_search($match.';', $_entities, TRUE)) !== FALSE)
					{
						$replace[$match] = $char;
					}
				}

				$str = str_ireplace(array_keys($replace), array_values($replace), $str);
			}
 
			$str = html_entity_decode(
				preg_replace('/(&#(?:x0*[0-9a-f]{2,5}(?![0-9a-f;])|(?:0*\d{2,4}(?![0-9;]))))/iS', '$1;', $str),
				$flag,
				$charset
			);
		}
		while ($str_compare !== $str);
		return $str;
	}
 
	public function sanitize_filename($str, $relative_path = FALSE)
	{
		$bad = $this->filename_bad_chars;

		if ( ! $relative_path)
		{
			$bad[] = './';
			$bad[] = '/';
		}

		$str = remove_invisible_characters($str, FALSE);

		do
		{
			$old = $str;
			$str = str_replace($bad, '', $str);
		}
		while ($old !== $str);

		return stripslashes($str);
	}
 
	public function strip_image_tags($str)
	{
		return preg_replace(array('#<img[\s/]+.*?src\s*=\s*["\'](.+?)["\'].*?\>#', '#<img[\s/]+.*?src\s*=\s*(.+?).*?\>#'), '\\1', $str);
	}
 
	protected function _compact_exploded_words($matches)
	{
		return preg_replace('/\s+/s', '', $matches[1]).$matches[2];
	}
 
	protected function _sanitize_naughty_html($matches)
	{
		static $naughty_tags    = array(
			'alert', 'prompt', 'confirm', 'applet', 'audio', 'basefont', 'base', 'behavior', 'bgsound',
			'blink', 'body', 'embed', 'expression', 'form', 'frameset', 'frame', 'head', 'html', 'ilayer',
			'iframe', 'input', 'button', 'select', 'isindex', 'layer', 'link', 'meta', 'keygen', 'object',
			'plaintext', 'style', 'script', 'textarea', 'title', 'math', 'video', 'svg', 'xml', 'xss'
		);

		static $evil_attributes = array(
			'on\w+', 'style', 'xmlns', 'formaction', 'form', 'xlink:href', 'FSCommand', 'seekSegmentTime'
		);
 
		if (empty($matches['closeTag']))
		{
			return '&lt;'.$matches[1];
		}
 
		elseif (in_array(strtolower($matches['tagName']), $naughty_tags, TRUE))
		{
			return '&lt;'.$matches[1].'&gt;';
		}
 
		elseif (isset($matches['attributes']))
		{
 
			$attributes = array();
 
			$attributes_pattern = '#'
				.'(?<name>[^\s\042\047>/=]+)' // attribute characters
 
				.'(?:\s*=(?<value>[^\s\042\047=><`]+|\s*\042[^\042]*\042|\s*\047[^\047]*\047|\s*(?U:[^\s\042\047=><`]*)))' // attribute-value separator
				.'#i';
 
			$is_evil_pattern = '#^('.implode('|', $evil_attributes).')$#i';
 
			do
			{
 
 
 
				$matches['attributes'] = preg_replace('#^[^a-z]+#i', '', $matches['attributes']);

				if ( ! preg_match($attributes_pattern, $matches['attributes'], $attribute, PREG_OFFSET_CAPTURE))
				{
 
					break;
				}

				if (
 
					preg_match($is_evil_pattern, $attribute['name'][0])
 
					OR (trim($attribute['value'][0]) === '')
				)
				{
					$attributes[] = 'xss=removed';
				}
				else
				{
					$attributes[] = $attribute[0][0];
				}

				$matches['attributes'] = substr($matches['attributes'], $attribute[0][1] + strlen($attribute[0][0]));
			}
			while ($matches['attributes'] !== '');

			$attributes = empty($attributes)
				? ''
				: ' '.implode(' ', $attributes);
			return '<'.$matches['slash'].$matches['tagName'].$attributes.'>';
		}

		return $matches[0];
	}
 
	protected function _js_link_removal($match)
	{
		return str_replace(
			$match[1],
			preg_replace(
				'#href=.*?(?:(?:alert|prompt|confirm)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|data\s*:)#si',
				'',
				$this->_filter_attributes($match[1])
			),
			$match[0]
		);
	}
 
	protected function _js_img_removal($match)
	{
		return str_replace(
			$match[1],
			preg_replace(
				'#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si',
				'',
				$this->_filter_attributes($match[1])
			),
			$match[0]
		);
	}
 
	protected function _convert_attribute($match)
	{
		return str_replace(array('>', '<', '\\'), array('&gt;', '&lt;', '\\\\'), $match[0]);
	}
 
	protected function _filter_attributes($str)
	{
		$out = '';
		if (preg_match_all('#\s*[a-z\-]+\s*=\s*(\042|\047)([^\\1]*?)\\1#is', $str, $matches))
		{
			foreach ($matches[0] as $match)
			{
				$out .= preg_replace('#/\*.*?\*/#s', '', $match);
			}
		}

		return $out;
	}
 
	protected function _decode_entity($match)
	{
 
 
		$match = preg_replace('|\&([a-z\_0-9\-]+)\=([a-z\_0-9\-/]+)|i', $this->xss_hash().'\\1=\\2', $match[0]);
 
		return str_replace(
			$this->xss_hash(),
			'&',
			$this->entity_decode($match, $this->charset)
		);
	}
 
	protected function _do_never_allowed($str)
	{
		$str = str_replace(array_keys($this->_never_allowed_str), $this->_never_allowed_str, $str);

		foreach ($this->_never_allowed_regex as $regex)
		{
			$str = preg_replace('#'.$regex.'#is', '[removed]', $str);
		}

		return $str;
	}
 
	protected function _csrf_set_hash()
	{
		if ($this->_csrf_hash === NULL)
		{
 
 
 
 
			if (isset($_COOKIE[$this->_csrf_cookie_name]) && is_string($_COOKIE[$this->_csrf_cookie_name])
				&& preg_match('#^[0-9a-f]{32}$#iS', $_COOKIE[$this->_csrf_cookie_name]) === 1)
			{
				return $this->_csrf_hash = $_COOKIE[$this->_csrf_cookie_name];
			}

			$rand = $this->get_random_bytes(16);
			$this->_csrf_hash = ($rand === FALSE)
				? md5(uniqid(mt_rand(), TRUE))
				: bin2hex($rand);
		}

		return $this->_csrf_hash;
	}

}
