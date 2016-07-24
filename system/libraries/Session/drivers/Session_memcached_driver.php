<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class CI_Session_memcached_driver extends CI_Session_driver implements SessionHandlerInterface {

	
	protected $_memcached;

	
	protected $_key_prefix = 'ci_session:';

	
	protected $_lock_key;


	
	public function __construct(&$params)
	{
		parent::__construct($params);

		if (empty($this->_config['save_path']))
		{
			log_message('error', 'Session: No Memcached save path configured.');
		}

		if ($this->_config['match_ip'] === TRUE)
		{
			$this->_key_prefix .= $_SERVER['REMOTE_ADDR'].':';
		}
	}


	
	public function open($save_path, $name)
	{
		$this->_memcached = new Memcached();
		$this->_memcached->setOption(Memcached::OPT_BINARY_PROTOCOL, TRUE); // required for touch() usage
		$server_list = array();
		foreach ($this->_memcached->getServerList() as $server)
		{
			$server_list[] = $server['host'].':'.$server['port'];
		}

		if ( ! preg_match_all('#,?([^,:]+)\:(\d{1,5})(?:\:(\d+))?#', $this->_config['save_path'], $matches, PREG_SET_ORDER))
		{
			$this->_memcached = NULL;
			log_message('error', 'Session: Invalid Memcached save path format: '.$this->_config['save_path']);
			return $this->_failure;
		}

		foreach ($matches as $match)
		{

			if (in_array($match[1].':'.$match[2], $server_list, TRUE))
			{
				log_message('debug', 'Session: Memcached server pool already has '.$match[1].':'.$match[2]);
				continue;
			}

			if ( ! $this->_memcached->addServer($match[1], $match[2], isset($match[3]) ? $match[3] : 0))
			{
				log_message('error', 'Could not add '.$match[1].':'.$match[2].' to Memcached server pool.');
			}
			else
			{
				$server_list[] = $match[1].':'.$match[2];
			}
		}

		if (empty($server_list))
		{
			log_message('error', 'Session: Memcached server pool is empty.');
			return $this->_failure;
		}

		return $this->_success;
	}


	
	public function read($session_id)
	{
		if (isset($this->_memcached) && $this->_get_lock($session_id))
		{

			$this->_session_id = $session_id;

			$session_data = (string) $this->_memcached->get($this->_key_prefix.$session_id);
			$this->_fingerprint = md5($session_data);
			return $session_data;
		}

		return $this->_failure;
	}


	
	public function write($session_id, $session_data)
	{
		if ( ! isset($this->_memcached))
		{
			return $this->_failure;
		}

		elseif ($session_id !== $this->_session_id)
		{
			if ( ! $this->_release_lock() OR ! $this->_get_lock($session_id))
			{
				return $this->_failure;
			}

			$this->_fingerprint = md5('');
			$this->_session_id = $session_id;
		}

		if (isset($this->_lock_key))
		{
			$this->_memcached->replace($this->_lock_key, time(), 300);
			if ($this->_fingerprint !== ($fingerprint = md5($session_data)))
			{
				if ($this->_memcached->set($this->_key_prefix.$session_id, $session_data, $this->_config['expiration']))
				{
					$this->_fingerprint = $fingerprint;
					return $this->_success;
				}

				return $this->_failure;
			}

			return $this->_memcached->touch($this->_key_prefix.$session_id, $this->_config['expiration'])
				? $this->_success
				: $this->_failure;
		}

		return $this->_failure;
	}


	
	public function close()
	{
		if (isset($this->_memcached))
		{
			isset($this->_lock_key) && $this->_memcached->delete($this->_lock_key);
			if ( ! $this->_memcached->quit())
			{
				return $this->_failure;
			}

			$this->_memcached = NULL;
			return $this->_success;
		}

		return $this->_failure;
	}


	
	public function destroy($session_id)
	{
		if (isset($this->_memcached, $this->_lock_key))
		{
			$this->_memcached->delete($this->_key_prefix.$session_id);
			$this->_cookie_destroy();
			return $this->_success;
		}

		return $this->_failure;
	}


	
	public function gc($maxlifetime)
	{

		return $this->_success;
	}


	
	protected function _get_lock($session_id)
	{
		if (isset($this->_lock_key))
		{
			return ($this->_memcached->replace($this->_lock_key, time(), 300))
				? $this->_success
				: $this->_failure;
		}

		$lock_key = $this->_key_prefix.$session_id.':lock';
		$attempt = 0;
		do
		{
			if ($this->_memcached->get($lock_key))
			{
				sleep(1);
				continue;
			}

			if ( ! $this->_memcached->set($lock_key, time(), 300))
			{
				log_message('error', 'Session: Error while trying to obtain lock for '.$this->_key_prefix.$session_id);
				return $this->_failure;
			}

			$this->_lock_key = $lock_key;
			break;
		}
		while (++$attempt < 30);

		if ($attempt === 30)
		{
			log_message('error', 'Session: Unable to obtain lock for '.$this->_key_prefix.$session_id.' after 30 attempts, aborting.');
			return $this->_failure;
		}

		$this->_lock = TRUE;
		return $this->_success;
	}


	
	protected function _release_lock()
	{
		if (isset($this->_memcached, $this->_lock_key) && $this->_lock)
		{
			if ( ! $this->_memcached->delete($this->_lock_key) && $this->_memcached->getResultCode() !== Memcached::RES_NOTFOUND)
			{
				log_message('error', 'Session: Error while trying to free lock for '.$this->_lock_key);
				return FALSE;
			}

			$this->_lock_key = NULL;
			$this->_lock = FALSE;
		}

		return TRUE;
	}

}
