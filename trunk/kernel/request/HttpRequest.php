<?php

class HttpRequest extends Object
{
	private $get = NULL;

	private $post = NULL;

	private $cookie = NULL;

	private $file = NULL;

	private $header = NULL;


	private static $isInitialize = FALSE;

	private static $instance = NULL;

    /**
     * Vraci vlastni instanci
     *
     * @return HttpRequest
     */
	public static function getInstance() {
		if(NULL === self::$instance) {
			return self::$instance = new HttpRequest;
		} else {
			return self::$instance;
		}
	}

	public function __construct()
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
			$this->setGet();
			$this->setPost();
			$this->setCookie();
			$this->setFile();
			$this->setHeader();
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
        return $_SERVER['REQUEST_URI'];
    }

    public static function redirect($url, $responseCode = '302')
    {
        $url = htmlspecialchars($url);
        header('Location: ' . $url, $responseCode);
        echo '<p><a href="' .$url . '">Prosim nasledujte tento link.</a>';
    }

    /**
     * Vrati objekt HttpGet.
     * Pokud neni nastaven nastavi jej.
     *
     * @return HttpGet
     */
	public function getGet()
	{
		if (NULL === $this->get) {
			$this->setGet();
		}
		return $this->get;
	}

	private function setGet()
	{
		$this->get = new HttpGet;
		$this->get->assign($_GET);
		unset($_GET);
	}

    /**
     * Vrati HttpPost.
     * Pokud neni nastaven nastavi ho.
     *
     * @return return HttpPost
     */
	public function getPost()
	{
		if(NULL === $this->post) {
			$this->setPost();
		}

		return $this->post;
	}

	private function setPost()
	{
		$this->post = new HttpPost;
		$this->post->assign($_POST);
		unset($_POST);
	}

	public function getCookie()
	{
		if (NULL === $this->cookie) {
			$this->setCookie();
		}

		return $this->cookie;
	}

	private function setCookie()
	{
		$this->cookie = new HttpCookie;
		$this->cookie->assign($_COOKIE);
		unset($_COOKIE);
	}

	public function getFile()
	{
		if (NULL === $this->file) {
			$this-setFile();
		}

		return $this->file;
	}

	private function setFile()
	{
		$this->file = new HttpFile;
		$this->file->assign($_FILES);
		unset($_FILES);
	}

    
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

	private function setHeader()
	{
		$this->header = new HttpHeader();
		$this->header->assign();
	}

}