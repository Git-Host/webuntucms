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

	public function getHeader()
	{
		if (NULL === $this->header) {
			$this->setHeader();
		}

		return $this->header;
	}

	private function setHeader()
	{
		$this->header = new HttpHeader;
		$this->header->assign();
	}

}