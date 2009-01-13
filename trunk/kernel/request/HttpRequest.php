<?php

class Kernel_Request_HttpRequest extends Object
{
	private $get = NULL;

	private $post = NULL;

	private $cookie = NULL;

	private $files = array();

	private $header = NULL;

    private $encoding = 'UTF-8';

    /**
     * Promena se kterou se pracuje v ramci getu, ovlada celeho BOBRa :)
     *
     * @var string
     */
    const GET_VARIABLE = 'bobrquery';


	private static $isInitialize = FALSE;

	private static $instance = NULL;

    /**
     * Vraci vlastni instanci
     *
     * @return Kernel_Request_HttpRequest
     */
	public static function getInstance() {
		if(NULL === self::$instance) {
			return self::$instance = new self;
		} else {
			return self::$instance;
		}
	}

	private function __construct()
	{
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



	private function init()
	{
		if (FALSE === self::$isInitialize) {
            $this->setGet()
                ->setPost()
                ->setCookie()
                ->setFiles()
                ->setHeader();
		}
	}

    /**
     * Zjisti jestli se jedna o ajaxovej request
     *
     * @return boolen
     */
    public function isAjax()
    {
        return $this->getHeader('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Vrati request Uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->getGet()->{self::GET_VARIABLE};
    }

    public static function redirect($url, $responseCode = '302')
    {
        $url = htmlspecialchars($url);
        file_put_contents(__WEB_ROOT__ . '/local/cache/' . time(), $url);
        header('Location: ' . $url, $responseCode);
        // Pro pripad, ze by byla odeslana hlavicka.
        echo '<p><a href="' .$url . '">Prosim nasledujte tento link.</a>';
    }

    /**
     * Vrati aktualni lang v GETu
     *
     * @return string
     */
    public static function lang()
    {
        return self::getInstance()->getGet()->getLang();
    }

    /**
     * Vrati uri
     *
     * @return string
     */
    public static function uri()
    {
        return self::getInstance()->getUri();
    }

    /**
     * Vrati object HttpGet
     * 
     * @return Kernel_Request_HttpGet
     */
    public static function get()
    {
        return self::getInstance()->getGet();
    }

    /**
     * Vrati object HttpPost
     *
     * @return Kernel_Request_HttpPost
     */
    public static function post()
    {
        return self::getInstance()->getPost();
    }

    /**
     * Vrati kolekci objektu HttpFile
     *
     * @return array
     */
    public static function files()
    {
        return self::getInstance()->getFiles();
    }

    /**
	 * Returns HTTP request method (GET, POST, HEAD, PUT, ...). The method is case-sensitive.
     *
	 * @return string
	 */
	public function getMethod()
	{
		return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : NULL;
	}



	/**
	 * Checks if the request method is the given one.
     *
	 * @param $method string
	 * @return bool
	 */
	public function isMethod($method)
	{
		return isset($_SERVER['REQUEST_METHOD']) ? strcasecmp($_SERVER['REQUEST_METHOD'], $method) === 0 : FALSE;
	}



	/**
	 * Kontroluje zda-li request metoda je POST.
     *
	 * @return boolean
	 */
	public function isPost()
	{
		return $this->isMethod('POST');
	}

    /**
	 * Nastavi kodovani.
     *
	 * @param  $encodint string
	 * @return Kernel_Request_HttpRequest
	 */
	public function setEncoding($encoding)
	{
		if (strcasecmp($encoding, $this->encoding)) {
			$this->encoding = $encoding;
		}
        return $this;
	}

    /**
     * Vrati objekt HttpGet.
     * Pokud neni nastaven nastavi jej.
     *
     * @return Kernel_Request_HttpGet
     */
	public function getGet()
	{
		if (NULL === $this->get) {
			$this->setGet();
		}
		return $this->get;
	}

    /**
     * Nastavi objekt HttpGet a odnastavi globalni promenou $_GET
     *
     * @return Kernel_Request_HttpRequest
     */
	private function setGet()
	{
		$this->get = new Kernel_Request_HttpGet;
		$this->get->assign($_GET);
		unset($_GET);
        return $this;
	}

    /**
     * Vrati HttpPost.
     * Pokud neni nastaven nastavi ho.
     *
     * @return return Kernel_Request_HttpPost
     */
	public function getPost()
	{
		if(NULL === $this->post) {
			$this->setPost();
		}

		return $this->post->getPost();
	}

    /**
     * Nastavi objekt HttpGet a odnastavi globalni promenou $_POST
     *
     * @return Kernel_Request_HttpRequest
     */
	private function setPost()
	{
		$this->post = new Kernel_Request_HttpPost($_POST);
		unset($_POST);
        return $this;
	}

	public function getCookie()
	{
		if (NULL === $this->cookie) {
			$this->setCookie();
		}

		return $this->cookie;
	}

    /**
     * Nastavi objekt HttpCookie a odnastavi globalni promenou $_COOKIE
     *
     * @return Kernel_Request_HttpRequest
     */
	private function setCookie()
	{
		$this->cookie = new Kernel_Request_HttpCookie;
		$this->cookie->assign($_COOKIE);
		unset($_COOKIE);
        return $this;
	}

	public function getFiles()
	{
		if (empty($this->files)) {
			$this->setFiles();
		}

		return $this->files;
	}

    /**
     * Nastavi objekt HttpFile a odnastavi globalni promenou $_FILES.
     * Object HttpFile je prevzat z Nette.
     *
     * @return Kernel_Request_HttpRequest
     */
	private function setFiles()
	{
       if (empty($_FILES)) {
           $this->files['empty'] = new Kernel_Request_HttpFile($_FILES);
       } else {
           foreach ($_FILES as $name => $file) {
                if (isset($file['name'])) {
                    $this->files[$name] = new Kernel_Request_HttpFile($_FILES[$name]);
                } else {
                    throw new InvalidArgumentException('Spatny format formularoveho prvku file. Tento zpusob implementace neni podporovan.');
                }
            }
       }
       unset($_FILES);
       return $this;
	}

    /**
     * Vraci hodnotu hlavicky pokud existuje. Jinak vrati FALSE.
     * Kdyz nejsou hlavicky nastavene nastavi je.
     *
     * @param string $header
     * @return mixed
     */
	public function getHeader($header = NULL)
	{
		if (NULL === $this->header) {
			$this->setHeader();
		}

        if (NULL === $header) {
            return $this->header;
        }


        if (isset($this->header->$header)) {
            return $this->header->$header;
        }

        return FALSE;
	}

    /**
     * Nastavi objekt HttpHeader
     *
     * @return HttpRequest
     */
	private function setHeader()
	{
		$this->header = new Kernel_Request_HttpHeader();
		$this->header->assign();
        return $this;
	}

}