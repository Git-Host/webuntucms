<?php
/**
 * Request slouzi jako
 * kolekce metod pro ovladani requestu
 *
 */
class Request extends Object
{
	// Do techto promenych se ukladaji data z globalu
	protected
				$GET,
				$POST,
				/**
				 * Do $HEADERS se ukladaji hlavicky co poslal server
				 */
				$HEADERS = array(),
				/**
				 * REMOTE ADDRES
				 */
				$IP,
				/**
				 * HTTP REFERER
				 */
				$REFERER,
				/**
				 * SERVER PORT
				 */
				$PORT,
				/**
				 * HTTP PROTOCOL
				 */
				$PROTOCOL,
				/**
				 * DOCUMENT ROOT
				 */
				$DOCUMENTROOT,
				/**
				 * is Ajax bool
				 */
				$ISAJAX = FALSE,
				/**
				 * REQUEST LANG PREFERER
				 */
				$LANG,
				/**
				 * REDIRECT_URL
				 */
				$REDIRECT_URL;

	protected function __construct( $session )
	{
		$this->setRequest();
		/**
		 * Zjistujem jestli v postu je login a pass.
		 * Konsnstruktor teto tridy se dedi dal.
		 */
		$config = BobrConf::getInstance();
		if( isset( $this->POST['login'] ) ){
			( TRUE === $session->tryLogin( $this->POST['login'], $this->POST['pass']) )
			? $this->redirect( $config['ADMIN_ROOT'])
			: NULL;
		}
		if( isset( $this->POST['logout'] ) ){
			( TRUE === $session->logoutSession() )
			? $this->redirect( $config['WEB_ROOT'])
			: NULL;
		}
	}

	/**
	 * Nastavi request do promenych a odnastavi globalni promene GET a POST
	 * @return
	 */
	private function setRequest()
	{
		$this->GET		=	$_GET;
		$this->POST		=	$_POST;
		// odnastavime globalni pole at s nim nemuze nikdo manipulovat
		unset( $_GET );
		unset( $_POST );

		// Ziskame hlavicky serveru
		if (function_exists('apache_request_headers')) {
			$this->HEADERS = array_change_key_case(apache_request_headers(), CASE_LOWER);
		} else {
			foreach ($_SERVER as $k => $v) {
				if (strncmp($k, 'HTTP_', 5) == 0) {
					$this->HEADERS[ strtr(strtolower(substr($k, 5)), '_', '-') ] = $v;
				}
			}
		}
		$this->IP			=	$_SERVER['REMOTE_ADDR'];
		isset($_SERVER['HTTP_REFERER']) ? $this->REFERER = $_SERVER['HTTP_REFERER'] : $this->REFERER = NULL;
		$this->PORT			=	$_SERVER['SERVER_PORT'];
		$this->PROTOCOL		=	$_SERVER['SERVER_PROTOCOL'];
		$this->DOCUMENTROOT	=	$_SERVER['DOCUMENT_ROOT'];

		$lang = explode( ';' , $this->HEADERS['accept-language'] );
		$lang = explode( ',', $lang[0] );
		$this->LANG			=	$lang[0];
		$this->REDIRECT_URL	=	isset( $_SERVER['REDIRECT_URL'] ) ? $_SERVER['REDIRECT_URL'] : NULL;

		/*
		 * Pri odesilani Ajaxovejch requestu pouzivame specialni hlavicku diky
		 * zde muzem identifikovat Ajaxovej poazadavek.
		 */
		if ( array_search('XMLHttpRequest', $this->HEADERS) ){
			 $this->ISAJAX = TRUE;
		}
	}

	/**
	 * Provede redirect pokud jiz nebyla odeslana hlavicka
	 * @return
	 * @param $location string
	 * @param $response integer cislo hlavicky ( 302 je redirect )
	 */
	public static function redirect( $location, $response = 302)
	{
		if (FALSE === headers_sent()){
			// @todo smazat ukladani $location do session
			$_SESSION['location'][] = $location;
			header ( 'Location: ' . $location, $response );
		}else {
			throw new HeaderException ( 'Nelze presmerovat protoze jiz byla odeslana hlavicka!!! Smůla co???' );
		}
	}

	public function getGET()
	{
		return $this->GET;
	}
	public function getPOST()
	{
		return $this->POST;
	}
	public function getHEADERS()
	{
		return $this->HEADERS;
	}
	public function getIP()
	{
		return $this->IP;
	}
	public function getREFERER()
	{
		return $this->REFERER;
	}
	public function getPORT()
	{
		return $this->PORT;
	}
	public function getPROTOCOL()
	{
		return $this->PROTOCOL;
	}
	public function getDOCUMENTROOT()
	{
		return $this->DOCUMENTROOT;
	}
	public function getISAJAX()
	{
		return $this->ISAJAX;
	}
	public function getLANG()
	{
		return $this->LANG;
	}
	public function getREDIRECT_URL()
	{
		return $this->REDIRECT_URL;
	}
}
