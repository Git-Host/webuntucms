<?php

/**
 * dibi - tiny'n'smart database abstraction layer
 * ----------------------------------------------
 *
 * Copyright (c) 2005, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "dibi license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://dibiphp.com
 *
 * @copyright  Copyright (c) 2005, 2008 David Grudl
 * @license    http://dibiphp.com/license  dibi license
 * @link       http://dibiphp.com
 * @package    dibi
 * @version    $Id: postgre.php 133 2008-07-17 03:51:29Z David Grudl $
 */


/**
 * The dibi driver for PostgreSQL database.
 *
 * Connection options:
 *   - 'host','hostaddr','port','dbname','user','password','connect_timeout','options','sslmode','service' - see PostgreSQL API
 *   - 'string' - or use connection string
 *   - 'persistent' - try to find a persistent link?
 *   - 'charset' - character encoding to set
 *   - 'schema' - the schema search path
 *   - 'lazy' - if TRUE, connection will be established only when required
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2005, 2008 David Grudl
 * @package    dibi
 */
class DibiPostgreDriver extends /*Nette::*/Object implements IDibiDriver
{

	/**
	 * Connection resource.
	 * @var resource
	 */
	private $connection;


	/**
	 * Resultset resource.
	 * @var resource
	 */
	private $resultSet;


	/**
	 * Escape method.
	 * @var bool
	 */
	private $escMethod = FALSE;



	/**
	 * @throws DibiException
	 */
	public function __construct()
	{
		if (!extension_loaded('pgsql')) {
			throw new DibiDriverException("PHP extension 'pgsql' is not loaded.");
		}
	}



	/**
	 * Connects to a database.
	 *
	 * @return void
	 * @throws DibiException
	 */
	public function connect(array &$config)
	{
		if (isset($config['string'])) {
			$string = $config['string'];
		} else {
			$string = '';
			foreach (array('host','hostaddr','port','dbname','user','password','connect_timeout','options','sslmode','service') as $key) {
				if (isset($config[$key])) $string .= $key . '=' . $config[$key] . ' ';
			}
		}

		DibiDriverException::tryError();
		if (isset($config['persistent'])) {
			$this->connection = pg_connect($string, PGSQL_CONNECT_FORCE_NEW);
		} else {
			$this->connection = pg_pconnect($string, PGSQL_CONNECT_FORCE_NEW);
		}
		if (DibiDriverException::catchError($msg)) {
			throw new DibiDriverException($msg, 0);
		}

		if (!is_resource($this->connection)) {
			throw new DibiDriverException('Connecting error.');
		}

		if (isset($config['charset'])) {
			DibiDriverException::tryError();
			pg_set_client_encoding($this->connection, $config['charset']);
			if (DibiDriverException::catchError($msg)) {
				throw new DibiDriverException($msg, 0);
			}
		}

		if (isset($config['schema'])) {
			$this->query('SET search_path TO ' . $config['schema']);
		}

		$this->escMethod = version_compare(PHP_VERSION , '5.2.0', '>=');
	}



	/**
	 * Disconnects from a database.
	 *
	 * @return void
	 */
	public function disconnect()
	{
		pg_close($this->connection);
	}



	/**
	 * Executes the SQL query.
	 *
	 * @param  string      SQL statement.
	 * @param  bool        update affected rows?
	 * @return IDibiDriver|NULL
	 * @throws DibiDriverException
	 */
	public function query($sql)
	{
		$this->resultSet = @pg_query($this->connection, $sql); // intentionally @

		if ($this->resultSet === FALSE) {
			throw new DibiDriverException(pg_last_error($this->connection), 0, $sql);
		}

		return is_resource($this->resultSet) && pg_num_fields($this->resultSet) ? clone $this : NULL;
	}



	/**
	 * Gets the number of affected rows by the last INSERT, UPDATE or DELETE query.
	 *
	 * @return int|FALSE  number of rows or FALSE on error
	 */
	public function affectedRows()
	{
		return pg_affected_rows($this->resultSet);
	}



	/**
	 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
	 *
	 * @return int|FALSE  int on success or FALSE on failure
	 */
	public function insertId($sequence)
	{
		if ($sequence === NULL) {
			// PostgreSQL 8.1 is needed
			$has = $this->query("SELECT LASTVAL()");
		} else {
			$has = $this->query("SELECT CURRVAL('$sequence')");
		}

		if (!$has) return FALSE;

		$row = $this->fetch(FALSE);
		$this->free();
		return is_array($row) ? $row[0] : FALSE;
	}



	/**
	 * Begins a transaction (if supported).
	 * @return void
	 * @throws DibiDriverException
	 */
	public function begin()
	{
		$this->query('START TRANSACTION');
	}



	/**
	 * Commits statements in a transaction.
	 * @return void
	 * @throws DibiDriverException
	 */
	public function commit()
	{
		$this->query('COMMIT');
	}



	/**
	 * Rollback changes in a transaction.
	 * @return void
	 * @throws DibiDriverException
	 */
	public function rollback()
	{
		$this->query('ROLLBACK');
	}



	/**
	 * Encodes data for use in an SQL statement.
	 *
	 * @param  string    value
	 * @param  string    type (dibi::FIELD_TEXT, dibi::FIELD_BOOL, ...)
	 * @return string    encoded value
	 * @throws InvalidArgumentException
	 */
	public function escape($value, $type)
	{
		switch ($type) {
		case dibi::FIELD_TEXT:
			if ($this->escMethod) {
				return "'" . pg_escape_string($this->connection, $value) . "'";
			} else {
				return "'" . pg_escape_string($value) . "'";
			}

		case dibi::FIELD_BINARY:
			if ($this->escMethod) {
				return "'" . pg_escape_bytea($this->connection, $value) . "'";
			} else {
				return "'" . pg_escape_bytea($value) . "'";
			}

		case dibi::IDENTIFIER:
			$a = strrpos($value, '.');
			if ($a === FALSE) {
				return '"' . str_replace('"', '""', $value) . '"';
			} else {
				// table.col delimite as table."col"
				return substr($value, 0, $a) . '."' . str_replace('"', '""', substr($value, $a + 1)) . '"';
			}

		case dibi::FIELD_BOOL:
			return $value ? 'TRUE' : 'FALSE';

		case dibi::FIELD_DATE:
			return date("'Y-m-d'", $value);

		case dibi::FIELD_DATETIME:
			return date("'Y-m-d H:i:s'", $value);

		default:
			throw new InvalidArgumentException('Unsupported type.');
		}
	}



	/**
	 * Decodes data from result set.
	 *
	 * @param  string    value
	 * @param  string    type (dibi::FIELD_BINARY)
	 * @return string    decoded value
	 * @throws InvalidArgumentException
	 */
	public function unescape($value, $type)
	{
		switch ($type) {
		case dibi::FIELD_BINARY:
			return pg_unescape_bytea($value);

		default:
			throw new InvalidArgumentException('Unsupported type.');
		}
	}



	/**
	 * Injects LIMIT/OFFSET to the SQL query.
	 *
	 * @param  string &$sql  The SQL query that will be modified.
	 * @param  int $limit
	 * @param  int $offset
	 * @return void
	 */
	public function applyLimit(&$sql, $limit, $offset)
	{
		if ($limit >= 0)
			$sql .= ' LIMIT ' . (int) $limit;

		if ($offset > 0)
			$sql .= ' OFFSET ' . (int) $offset;
	}



	/**
	 * Returns the number of rows in a result set.
	 *
	 * @return int
	 */
	public function rowCount()
	{
		return pg_num_rows($this->resultSet);
	}



	/**
	 * Fetches the row at current position and moves the internal cursor to the next position.
	 * internal usage only
	 *
	 * @param  bool     TRUE for associative array, FALSE for numeric
	 * @return array    array on success, nonarray if no next record
	 */
	public function fetch($type)
	{
		return pg_fetch_array($this->resultSet, NULL, $type ? PGSQL_ASSOC : PGSQL_NUM);
	}



	/**
	 * Moves cursor position without fetching row.
	 *
	 * @param  int      the 0-based cursor pos to seek to
	 * @return boolean  TRUE on success, FALSE if unable to seek to specified record
	 * @throws DibiException
	 */
	public function seek($row)
	{
		return pg_result_seek($this->resultSet, $row);
	}



	/**
	 * Frees the resources allocated for this result set.
	 *
	 * @return void
	 */
	public function free()
	{
		pg_free_result($this->resultSet);
		$this->resultSet = NULL;
	}



	/**
	 * Returns metadata for all columns in a result set.
	 *
	 * @return array
	 */
	public function getColumnsMeta()
	{
		$hasTable = version_compare(PHP_VERSION , '5.2.0', '>=');
		$count = pg_num_fields($this->resultSet);
		$meta = array();
		for ($i = 0; $i < $count; $i++) {
			// items 'name' and 'table' are required
			$meta[] = array(
				'name'      => pg_field_name($this->resultSet, $i),
				'table'     => $hasTable ? pg_field_table($this->resultSet, $i) : NULL,
				'type'      => pg_field_type($this->resultSet, $i),
				'size'      => pg_field_size($this->resultSet, $i),
				'prtlen'    => pg_field_prtlen($this->resultSet, $i),
			);
		}
		return $meta;
	}



	/**
	 * Returns the connection resource.
	 *
	 * @return mixed
	 */
	public function getResource()
	{
		return $this->connection;
	}



	/**
	 * Returns the result set resource.
	 *
	 * @return mixed
	 */
	public function getResultResource()
	{
		return $this->resultSet;
	}



	/**
	 * Gets a information of the current database.
	 *
	 * @return DibiReflection
	 */
	function getDibiReflection()
	{}

}
