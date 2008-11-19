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
 * @version    $Id: DibiConnection.php 133 2008-07-17 03:51:29Z David Grudl $
 */



/**
 * dibi connection.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2005, 2008 David Grudl
 * @package    dibi
 */
class DibiConnection extends /*Nette::*/Object
{
	/**
	 * Current connection configuration.
	 * @var array
	 */
	private $config;

	/**
	 * IDibiDriver.
	 * @var array
	 */
	private $driver;

	/**
	 * Is connected?
	 * @var bool
	 */
	private $connected = FALSE;

	/**
	 * Is in transaction?
	 * @var bool
	 */
	private $inTxn = FALSE;



	/**
	 * Creates object and (optionally) connects to a database.
	 *
	 * @param  array|string|Nette::Collections::Hashtable connection parameters
	 * @param  string       connection name
	 * @throws DibiException
	 */
	public function __construct($config, $name = NULL)
	{
		if (class_exists(/*Nette::*/'Debug', FALSE)) {
			/*Nette::*/Debug::addColophon(array('dibi', 'getColophon'));
		}

		// DSN string
		if (is_string($config)) {
			parse_str($config, $config);

		} elseif ($config instanceof /*Nette::Collections::*/Hashtable) {
			$config = (array) $config;

		} elseif (!is_array($config)) {
			throw new InvalidArgumentException('Configuration must be array, string or Nette::Collections::Hashtable.');
		}

		if (!isset($config['driver'])) {
			$config['driver'] = dibi::$defaultDriver;
		}

		$driver = preg_replace('#[^a-z0-9_]#', '_', $config['driver']);
		$class = "Dibi" . $driver . "Driver";
		if (!class_exists($class, FALSE)) {
			include_once __FILE__ . "/../../drivers/$driver.php";

			if (!class_exists($class, FALSE)) {
				throw new DibiException("Unable to create instance of dibi driver class '$class'.");
			}
		}

		if (isset($config['result:objects'])) {
			// normalize
			$val = $config['result:objects'];
			$config['result:objects'] = is_string($val) && !is_numeric($val) ? $val : (bool) $val;
		}

		$config['name'] = $name;
		$this->config = $config;
		$this->driver = new $class;

		if (empty($config['lazy'])) {
			$this->connect();
		}
	}



	/**
	 * Automatically frees the resources allocated for this result set.
	 *
	 * @return void
	 */
	public function __destruct()
	{
		// disconnects and rolls back transaction - do not rely on auto-disconnect and rollback!
		$this->disconnect();
	}



	/**
	 * Connects to a database.
	 *
	 * @return void
	 */
	final protected function connect()
	{
		if (!$this->connected) {
			$this->driver->connect($this->config);
			$this->connected = TRUE;
			dibi::notify($this, 'connected');
		}
	}



	/**
	 * Disconnects from a database.
	 *
	 * @return void
	 */
	final public function disconnect()
	{
		if ($this->connected) {
			if ($this->inTxn) {
				$this->rollback();
			}
			$this->driver->disconnect();
			$this->connected = FALSE;
			dibi::notify($this, 'disconnected');
		}
	}



	/**
	 * Returns TRUE when connection was established.
	 *
	 * @return bool
	 */
	final public function isConnected()
	{
		return $this->connected;
	}



	/**
	 * Returns configuration variable. If no $key is passed, returns the entire array.
	 *
	 * @see self::__construct
	 * @param  string
	 * @param  mixed  default value to use if key not found
	 * @return mixed
	 */
	final public function getConfig($key = NULL, $default = NULL)
	{
		if ($key === NULL) {
			return $this->config;

		} elseif (isset($this->config[$key])) {
			return $this->config[$key];

		} else {
			return $default;
		}
	}



	/**
	 * Apply configuration alias or default values.
	 *
	 * @param  array  connect configuration
	 * @param  string key
	 * @param  string alias key
	 * @return void
	 */
	public static function alias(&$config, $key, $alias=NULL)
	{
		if (isset($config[$key])) return;

		if ($alias !== NULL && isset($config[$alias])) {
			$config[$key] = $config[$alias];
			unset($config[$alias]);
		} else {
			$config[$key] = NULL;
		}
	}



	/**
	 * Returns the connection resource.
	 *
	 * @return resource
	 */
	final public function getResource()
	{
		return $this->driver->getResource();
	}



	/**
	 * Generates (translates) and executes SQL query.
	 *
	 * @param  array|mixed      one or more arguments
	 * @return DibiResult|NULL  result set object (if any)
	 * @throws DibiException
	 */
	final public function query($args)
	{
		$args = func_get_args();
		$this->connect();
		$trans = new DibiTranslator($this->driver);
		if ($trans->translate($args)) {
			return $this->nativeQuery($trans->sql);
		} else {
			throw new DibiException('SQL translate error: ' . $trans->sql);
		}
	}



	/**
	 * Generates and prints SQL query.
	 *
	 * @param  array|mixed  one or more arguments
	 * @return bool
	 */
	final public function test($args)
	{
		$args = func_get_args();
		$this->connect();
		$trans = new DibiTranslator($this->driver);
		$ok = $trans->translate($args);
		dibi::dump($trans->sql);
		return $ok;
	}



	/**
	 * Executes the SQL query.
	 *
	 * @param  string           SQL statement.
	 * @return DibiResult|NULL  result set object (if any)
	 * @throws DibiException
	 */
	final public function nativeQuery($sql)
	{
		$this->connect();

		dibi::$numOfQueries++;
		dibi::$sql = $sql;
		dibi::$elapsedTime = FALSE;
		$time = -microtime(TRUE);
		dibi::notify($this, 'beforeQuery', $sql);

		if ($res = $this->driver->query($sql)) { // intentionally =
			$res = new DibiResult($res, $this->config);
		}

		$time += microtime(TRUE);
		dibi::$elapsedTime = $time;
		dibi::$totalTime += $time;
		dibi::notify($this, 'afterQuery', $res);

		return $res;
	}



	/**
	 * Gets the number of affected rows by the last INSERT, UPDATE or DELETE query.
	 *
	 * @return int  number of rows
	 * @throws DibiException
	 */
	public function affectedRows()
	{
		$rows = $this->driver->affectedRows();
		if (!is_int($rows) || $rows < 0) throw new DibiException('Cannot retrieve number of affected rows.');
		return $rows;
	}



	/**
	 * Retrieves the ID generated for an AUTO_INCREMENT column by the previous INSERT query.
	 *
	 * @param  string     optional sequence name
	 * @return int
	 * @throws DibiException
	 */
	public function insertId($sequence = NULL)
	{
		$id = $this->driver->insertId($sequence);
		if ($id < 1) throw new DibiException('Cannot retrieve last generated ID.');
		return (int) $id;
	}



	/**
	 * Begins a transaction (if supported).
	 * @return void
	 */
	public function begin()
	{
		$this->connect();
		if ($this->inTxn) {
			throw new DibiException('There is already an active transaction.');
		}
		$this->driver->begin();
		$this->inTxn = TRUE;
		dibi::notify($this, 'begin');
	}



	/**
	 * Commits statements in a transaction.
	 * @return void
	 */
	public function commit()
	{
		if (!$this->inTxn) {
			throw new DibiException('There is no active transaction.');
		}
		$this->driver->commit();
		$this->inTxn = FALSE;
		dibi::notify($this, 'commit');
	}



	/**
	 * Rollback changes in a transaction.
	 * @return void
	 */
	public function rollback()
	{
		if (!$this->inTxn) {
			throw new DibiException('There is no active transaction.');
		}
		$this->driver->rollback();
		$this->inTxn = FALSE;
		dibi::notify($this, 'rollback');
	}



	/**
	 * Encodes data for use in an SQL statement.
	 *
	 * @param  string    unescaped string
	 * @param  string    type (dibi::FIELD_TEXT, dibi::FIELD_BOOL, ...)
	 * @return string    escaped and quoted string
	 */
	public function escape($value, $type = dibi::FIELD_TEXT)
	{
		$this->connect(); // MySQL & PDO require connection
		return $this->driver->escape($value, $type);
	}



	/**
	 * Decodes data from result set.
	 *
	 * @param  string    value
	 * @param  string    type (dibi::FIELD_BINARY)
	 * @return string    decoded value
	 */
	public function unescape($value, $type = dibi::FIELD_BINARY)
	{
		return $this->driver->unescape($value, $type);
	}



	/**
	 * Delimites identifier (table's or column's name, etc.).
	 *
	 * @param  string    identifier
	 * @return string    delimited identifier
	 */
	public function delimite($value)
	{
		return $this->driver->escape($value, dibi::IDENTIFIER);
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
		$this->driver->applyLimit($sql, $limit, $offset);
	}



	/**
	 * Import SQL dump from file - extreme fast!
	 *
	 * @param  string  filename
	 * @return int  count of sql commands
	 */
	public function loadFile($file)
	{
		$this->connect();

		@set_time_limit(0); // intentionally @

		$handle = @fopen($file, 'r'); // intentionally @
		if (!$handle) {
			throw new FileNotFoundException("Cannot open file '$file'.");
		}

		$count = 0;
		$sql = '';
		while (!feof($handle)) {
			$s = fgets($handle);
			$sql .= $s;
			if (substr(rtrim($s), -1) === ';') {
				$this->driver->query($sql);
				$sql = '';
				$count++;
			}
		}
		fclose($handle);
		return $count;
	}



	/**
	 * Gets a information of the current database.
	 *
	 * @return DibiReflection
	 */
	public function getDibiReflection()
	{
		throw new NotImplementedException;
	}



	/**
	 * Prevents unserialization.
	 */
	public function __wakeup()
	{
		throw new NotSupportedException('You cannot serialize or unserialize ' . $this->getClass() . ' instances.');
	}



	/**
	 * Prevents serialization.
	 */
	public function __sleep()
	{
		throw new NotSupportedException('You cannot serialize or unserialize ' . $this->getClass() . ' instances.');
	}

}
