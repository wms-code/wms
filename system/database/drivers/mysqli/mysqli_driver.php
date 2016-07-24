<?php

defined('BASEPATH') OR exit('No direct script access allowed');


class CI_DB_mysqli_driver extends CI_DB {

	
	public $dbdriver = 'mysqli';

	
	public $compress = FALSE;

	
	public $delete_hack = TRUE;

	
	public $stricton = FALSE;


	
	protected $_escape_char = '`';


	
	public function db_connect($persistent = FALSE)
	{

		if ($this->hostname[0] === '/')
		{
			$hostname = NULL;
			$port = NULL;
			$socket = $this->hostname;
		}
		else
		{

			$hostname = ($persistent === TRUE && is_php('5.3'))
				? 'p:'.$this->hostname : $this->hostname;
			$port = empty($this->port) ? NULL : $this->port;
			$socket = NULL;
		}

		$client_flags = ($this->compress === TRUE) ? MYSQLI_CLIENT_COMPRESS : 0;
		$mysqli = mysqli_init();

		$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10);

		if ($this->stricton)
		{
			$mysqli->options(MYSQLI_INIT_COMMAND, 'SET SESSION sql_mode="STRICT_ALL_TABLES"');
		}

		if (is_array($this->encrypt))
		{
			$ssl = array();
			empty($this->encrypt['ssl_key'])    OR $ssl['key']    = $this->encrypt['ssl_key'];
			empty($this->encrypt['ssl_cert'])   OR $ssl['cert']   = $this->encrypt['ssl_cert'];
			empty($this->encrypt['ssl_ca'])     OR $ssl['ca']     = $this->encrypt['ssl_ca'];
			empty($this->encrypt['ssl_capath']) OR $ssl['capath'] = $this->encrypt['ssl_capath'];
			empty($this->encrypt['ssl_cipher']) OR $ssl['cipher'] = $this->encrypt['ssl_cipher'];

			if ( ! empty($ssl))
			{
				if ( ! empty($this->encrypt['ssl_verify']) && defined('MYSQLI_OPT_SSL_VERIFY_SERVER_CERT'))
				{
					$mysqli->options(MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, TRUE);
				}

				$client_flags |= MYSQLI_CLIENT_SSL;
				$mysqli->ssl_set(
					isset($ssl['key'])    ? $ssl['key']    : NULL,
					isset($ssl['cert'])   ? $ssl['cert']   : NULL,
					isset($ssl['ca'])     ? $ssl['ca']     : NULL,
					isset($ssl['capath']) ? $ssl['capath'] : NULL,
					isset($ssl['cipher']) ? $ssl['cipher'] : NULL
				);
			}
		}

		if ($mysqli->real_connect($hostname, $this->username, $this->password, $this->database, $port, $socket, $client_flags))
		{

			if (
				($client_flags & MYSQLI_CLIENT_SSL)
				&& version_compare($mysqli->client_info, '5.7.3', '<=')
				&& empty($mysqli->query("SHOW STATUS LIKE 'ssl_cipher'")->fetch_object()->Value)
			)
			{
				$mysqli->close();
				$message = 'MySQLi was configured for an SSL connection, but got an unencrypted connection instead!';
				log_message('error', $message);
				return ($this->db->db_debug) ? $this->db->display_error($message, '', TRUE) : FALSE;
			}

			return $mysqli;
		}

		return FALSE;
	}


	
	public function reconnect()
	{
		if ($this->conn_id !== FALSE && $this->conn_id->ping() === FALSE)
		{
			$this->conn_id = FALSE;
		}
	}


	
	public function db_select($database = '')
	{
		if ($database === '')
		{
			$database = $this->database;
		}

		if ($this->conn_id->select_db($database))
		{
			$this->database = $database;
			return TRUE;
		}

		return FALSE;
	}


	
	protected function _db_set_charset($charset)
	{
		return $this->conn_id->set_charset($charset);
	}


	
	public function version()
	{
		if (isset($this->data_cache['version']))
		{
			return $this->data_cache['version'];
		}

		return $this->data_cache['version'] = $this->conn_id->server_info;
	}


	
	protected function _execute($sql)
	{
		return $this->conn_id->query($this->_prep_query($sql));
	}


	
	protected function _prep_query($sql)
	{


		if ($this->delete_hack === TRUE && preg_match('/^\s*DELETE\s+FROM\s+(\S+)\s*$/i', $sql))
		{
			return trim($sql).' WHERE 1=1';
		}

		return $sql;
	}


	
	protected function _trans_begin()
	{
		$this->conn_id->autocommit(FALSE);
		return is_php('5.5')
			? $this->conn_id->begin_transaction()
			: $this->simple_query('START TRANSACTION'); // can also be BEGIN or BEGIN WORK
	}


	
	protected function _trans_commit()
	{
		if ($this->conn_id->commit())
		{
			$this->conn_id->autocommit(TRUE);
			return TRUE;
		}

		return FALSE;
	}


	
	protected function _trans_rollback()
	{
		if ($this->conn_id->rollback())
		{
			$this->conn_id->autocommit(TRUE);
			return TRUE;
		}

		return FALSE;
	}


	
	protected function _escape_str($str)
	{
		return $this->conn_id->real_escape_string($str);
	}


	
	public function affected_rows()
	{
		return $this->conn_id->affected_rows;
	}


	
	public function insert_id()
	{
		return $this->conn_id->insert_id;
	}


	
	protected function _list_tables($prefix_limit = FALSE)
	{
		$sql = 'SHOW TABLES FROM '.$this->escape_identifiers($this->database);

		if ($prefix_limit !== FALSE && $this->dbprefix !== '')
		{
			return $sql." LIKE '".$this->escape_like_str($this->dbprefix)."%'";
		}

		return $sql;
	}


	
	protected function _list_columns($table = '')
	{
		return 'SHOW COLUMNS FROM '.$this->protect_identifiers($table, TRUE, NULL, FALSE);
	}


	
	public function field_data($table)
	{
		if (($query = $this->query('SHOW COLUMNS FROM '.$this->protect_identifiers($table, TRUE, NULL, FALSE))) === FALSE)
		{
			return FALSE;
		}
		$query = $query->result_object();

		$retval = array();
		for ($i = 0, $c = count($query); $i < $c; $i++)
		{
			$retval[$i]			= new stdClass();
			$retval[$i]->name		= $query[$i]->Field;

			sscanf($query[$i]->Type, '%[a-z](%d)',
				$retval[$i]->type,
				$retval[$i]->max_length
			);

			$retval[$i]->default		= $query[$i]->Default;
			$retval[$i]->primary_key	= (int) ($query[$i]->Key === 'PRI');
		}

		return $retval;
	}


	
	public function error()
	{
		if ( ! empty($this->conn_id->connect_errno))
		{
			return array(
				'code' => $this->conn_id->connect_errno,
				'message' => is_php('5.2.9') ? $this->conn_id->connect_error : mysqli_connect_error()
			);
		}

		return array('code' => $this->conn_id->errno, 'message' => $this->conn_id->error);
	}


	
	protected function _from_tables()
	{
		if ( ! empty($this->qb_join) && count($this->qb_from) > 1)
		{
			return '('.implode(', ', $this->qb_from).')';
		}

		return implode(', ', $this->qb_from);
	}


	
	protected function _close()
	{
		$this->conn_id->close();
	}

}
