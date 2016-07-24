<?php

defined('BASEPATH') OR exit('No direct script access allowed');

 
class CI_Session_files_driver extends CI_Session_driver implements SessionHandlerInterface {

	 
	protected $_save_path;

	 
	protected $_file_handle;

	 
	protected $_file_path;

	 
	protected $_file_new;
 

	 
	public function __construct(&$params)
	{
		parent::__construct($params);

		if (isset($this->_config['save_path']))
		{
			$this->_config['save_path'] = rtrim($this->_config['save_path'], '/\\');
			ini_set('session.save_path', $this->_config['save_path']);
		}
		else
		{
			$this->_config['save_path'] = rtrim(ini_get('session.save_path'), '/\\');
		}
	}
 

	 
	public function open($save_path, $name)
	{
		if ( ! is_dir($save_path))
		{
			if ( ! mkdir($save_path, 0700, TRUE))
			{
				throw new Exception("Session: Configured save path '".$this->_config['save_path']."' is not a directory, doesn't exist or cannot be created.");
			}
		}
		elseif ( ! is_writable($save_path))
		{
			throw new Exception("Session: Configured save path '".$this->_config['save_path']."' is not writable by the PHP process.");
		}

		$this->_config['save_path'] = $save_path;
		$this->_file_path = $this->_config['save_path'].DIRECTORY_SEPARATOR
			.$name // we'll use the session cookie name as a prefix to avoid collisions
			.($this->_config['match_ip'] ? md5($_SERVER['REMOTE_ADDR']) : '');

		return TRUE;
	}
 

	 
	public function read($session_id)
	{
 
 
		if ($this->_file_handle === NULL)
		{
 
 
 
			if (($this->_file_new = ! file_exists($this->_file_path.$session_id)) === TRUE)
			{
				if (($this->_file_handle = fopen($this->_file_path.$session_id, 'w+b')) === FALSE)
				{
					log_message('error', "Session: File '".$this->_file_path.$session_id."' doesn't exist and cannot be created.");
					return FALSE;
				}
			}
			elseif (($this->_file_handle = fopen($this->_file_path.$session_id, 'r+b')) === FALSE)
			{
				log_message('error', "Session: Unable to open file '".$this->_file_path.$session_id."'.");
				return FALSE;
			}

			if (flock($this->_file_handle, LOCK_EX) === FALSE)
			{
				log_message('error', "Session: Unable to obtain lock for file '".$this->_file_path.$session_id."'.");
				fclose($this->_file_handle);
				$this->_file_handle = NULL;
				return FALSE;
			}
 
			$this->_session_id = $session_id;

			if ($this->_file_new)
			{
				chmod($this->_file_path.$session_id, 0600);
				$this->_fingerprint = md5('');
				return '';
			}
		}
		else
		{
			rewind($this->_file_handle);
		}

		$session_data = '';
		for ($read = 0, $length = filesize($this->_file_path.$session_id); $read < $length; $read += strlen($buffer))
		{
			if (($buffer = fread($this->_file_handle, $length - $read)) === FALSE)
			{
				break;
			}

			$session_data .= $buffer;
		}

		$this->_fingerprint = md5($session_data);
		return $session_data;
	}
 

	 
	public function write($session_id, $session_data)
	{
 
 
		if ($session_id !== $this->_session_id && ( ! $this->close() OR $this->read($session_id) === FALSE))
		{
			return FALSE;
		}

		if ( ! is_resource($this->_file_handle))
		{
			return FALSE;
		}
		elseif ($this->_fingerprint === md5($session_data))
		{
			return ($this->_file_new)
				? TRUE
				: touch($this->_file_path.$session_id);
		}

		if ( ! $this->_file_new)
		{
			ftruncate($this->_file_handle, 0);
			rewind($this->_file_handle);
		}

		if (($length = strlen($session_data)) > 0)
		{
			for ($written = 0; $written < $length; $written += $result)
			{
				if (($result = fwrite($this->_file_handle, substr($session_data, $written))) === FALSE)
				{
					break;
				}
			}

			if ( ! is_int($result))
			{
				$this->_fingerprint = md5(substr($session_data, 0, $written));
				log_message('error', 'Session: Unable to write data.');
				return FALSE;
			}
		}

		$this->_fingerprint = md5($session_data);
		return TRUE;
	}
 

	 
	public function close()
	{
		if (is_resource($this->_file_handle))
		{
			flock($this->_file_handle, LOCK_UN);
			fclose($this->_file_handle);

			$this->_file_handle = $this->_file_new = $this->_session_id = NULL;
			return TRUE;
		}

		return TRUE;
	}
 

	 
	public function destroy($session_id)
	{
		if ($this->close())
		{
			return file_exists($this->_file_path.$session_id)
				? (unlink($this->_file_path.$session_id) && $this->_cookie_destroy())
				: TRUE;
		}
		elseif ($this->_file_path !== NULL)
		{
			clearstatcache();
			return file_exists($this->_file_path.$session_id)
				? (unlink($this->_file_path.$session_id) && $this->_cookie_destroy())
				: TRUE;
		}

		return FALSE;
	}
 

	 
	public function gc($maxlifetime)
	{
		if ( ! is_dir($this->_config['save_path']) OR ($directory = opendir($this->_config['save_path'])) === FALSE)
		{
			log_message('debug', "Session: Garbage collector couldn't list files under directory '".$this->_config['save_path']."'.");
			return FALSE;
		}

		$ts = time() - $maxlifetime;

		$pattern = sprintf(
			'/^%s[0-9a-f]{%d}$/',
			preg_quote($this->_config['cookie_name'], '/'),
			($this->_config['match_ip'] === TRUE ? 72 : 40)
		);

		while (($file = readdir($directory)) !== FALSE)
		{
 
			if ( ! preg_match($pattern, $file)
				OR ! is_file($this->_config['save_path'].DIRECTORY_SEPARATOR.$file)
				OR ($mtime = filemtime($this->_config['save_path'].DIRECTORY_SEPARATOR.$file)) === FALSE
				OR $mtime > $ts)
			{
				continue;
			}

			unlink($this->_config['save_path'].DIRECTORY_SEPARATOR.$file);
		}

		closedir($directory);

		return TRUE;
	}

}
