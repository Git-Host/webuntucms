<?php
final class Session //extends Object
{
	/**
	 * @var bool
	 */
	private static $started = FALSE;

	private $SESSION = array();

	private static $instance;

	/**
     * Vrati vlastni instanci.
     *
     * @return Session
     */
    public static function getInstance() {
		if(NULL === self::$instance) {
			return self::$instance = new Session;
		} else {
			self::$instance->rewrite();
			return self::$instance;
		}
	}

	private function __construct()
	{
		$this->start();
		$this->init();
	}

	/**
	 * Zakáže klonování singletonu.
	 *
	 * @throws LogicException při pokusu o klonování
	 */
	public final function __clone()
	{
		throw new LogicException(sprintf('Objekt třídy %s může mít pouze jednu instanci.', get_class($this)));
	}

	private function start()
	{
		if(FALSE === self::$started){
			session_start();
			self::$started = TRUE;
		}
	}

	private function init()
	{
		$this->SESSION = $_SESSION;
	}

	private function rewrite()
	{
		$_SESSION = $this->SESSION;
	}

	public function destroy()
	{
		$this->SESSION = array();
	}

	public function __destruct()
	{
		$this->rewrite();
	}

	public function __get($name)
	{
		if(isset($this->SESSION[$name])){
			return $this->SESSION[$name];
		}else{
			return NULL;
		}
	}

	public function __set($name, $value)
	{
		$this->SESSION[$name] = $value;
		return $this;
	}

	public function __unset($name)
	{
		unset($this->SESSION[$name]);
		return $this;
	}

	public function __isset($name)
	{
		return isset($this->SESSION[$name]);
	}
}