<?php
/**
 * Veskera prace se session se provadi prostrednictvim teto tridy.
 * Primy zapis do globalniho pole $_SESSION je ignorovan a smazan.
 */
final class Bobr_Session
{
    /**
     * Defaultni zivotnost 3 hodiny.
     *
     * @var integer
     */
	const DEFAULT_LIFETIME = 10800;
    
	/**
	 * @var bool
	 */
	private static $started = FALSE;

	private $SESSION = array();

	private static $instance;

	/**
     * Vrati vlastni instanci.
     *
     * @return Bobr_Session
     */
    public static function getInstance() {
		if(NULL === self::$instance) {
			return self::$instance = new self;
		} else {
			self::$instance->rewrite();
			return self::$instance;
		}
	}

	private function __construct()
	{
        ini_set('session.cookie_lifetime', '0');
		ini_set('session.cookie_path', '/');
		//ini_set('session.cookie_domain', '.' . $request->get('domain'));
		ini_set('session.cookie_secure', '0');
		ini_set('session.cookie_httponly', '1');
		ini_set('session.use_only_cookies', '1');
		ini_set('session.use_trans_sid', '0');
		ini_set('url_rewriter.tags', '');

        ini_set('session.gc_maxlifetime', self::DEFAULT_LIFETIME);// 3 hours
		ini_set('session_cache_limiter', 'none');// do not affect caching
		ini_set('session_cache_expire' , NULL);   // (default "180")
		ini_set('session.hash_function', NULL);  // (default "0", means MD5)
		ini_set('session.hash_bits_per_character', NULL); // (default "4")

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

    /**
     * Vrati aktualni lang ktery je v session
     *
     * @return string
     */
    public static function lang()
    {
        return self::getInstance()->lang;
    }

    public static function getNamespace($namespace)
    {
        if (isset(self::getInstance()->SESSION['bobr'][$namespace])) {
            return self::getInstance()->SESSION['bobr'][$namespace];
        } else {
            return NULL;
        }
    }

    public static function addNamesapce($namespace)
    {
        self::getInstance()->SESSION['bobr'][$namespace] = array();
    }

    public static function setNamesapce($namespace, $value)
    {
        self::getInstance()->SESSION['bobr'][$namespace] = $value;
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