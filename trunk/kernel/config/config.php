<?php
/**
 * V teto tride se definuji konstanty
 */
class BobrConf extends Settings
{
	const WEB_ENCODING			= 'utf-8';
	
	/*
	 * Pripojeni na databazi
	 */
	const DB_HOST				= 'localhost';
	const DB_PORT				= '5432';
	const DB_NAME				= 'bobr_devel';
	const DB_USER				= 'postgres';
	const DB_PASSWORD			= 'webuntu';
	const DB_PERSISTENT			= TRUE;
	const DB_CONNECTION_NAME	= 'default';
	const DB_PREFIX				= 'bobr_';
	
	const CACHE_ROOT			= 'cache/';
	const DEBUG_MODE			= TRUE;
	// Share url je zde mysleno ke slozce share. Vetsinou je url jen /
	const SHARE_URL				= '/';

	
	
	private static $instance = FALSE;
	
	// @todo tohle by se melo tahat z databaze
	private $settings = array(
		'WEB_TITLE'				=>	'BOBR COPR 2.0 DEVEL',
		'WEB_TITLE_REVERT'		=>	FALSE,
		'WEB_TITLE_SEPARATOR'	=>	'-',
		'WEB_META_KEYWORDS'		=>	'bobr, copr, punk, junk',
		'WEB_META_DESCRIPTION'	=>	'BOBR Pičo je nejlepšejší a pošle všechno do COPRu i DRUPAListy ;)',
		'WEB_AUTHOR'			=>	'BOBR COPR',
		'WEB_WEBMASTER'			=>	'BOBR PIČO',
		'WEB_COPYRIGHT'			=>	'BOBR',
		'WEB_FAVICON'			=>	'favicon.ico',
		'WEB_LANG'				=>	'1',
		'WEB_PAGEID_DEFAULT'	=>	'1',
		'WEB_ROOT'				=>	'/',
		// administrace default data
		'ADMIN_ROOT'			=>	'/bobradmin/',
		'ADMIN_LANG'			=>	'1',
		'ADMIN_PAGEID_DEFAULT'	=>	'3',
		// ostatni data
		'TIME_FORMAT'			=>	'd/m/Y - H:i',
		'FORBIDDEN_PAGE'		=>	'http://redtube.com',
		'LOGGING_CACHE'			=>	FALSE,
		
	);
	
	public static function getSingleton() {
		if( self::$instance === FALSE ) {
			self::$instance = new BobrConf();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->setIconvEncoding();
	}
		
	function offsetGet ( $value )
	{
		$value = strtoupper( $value );
		if( array_key_exists( $value, $this->settings ) ){
			return $this->settings[ $value ];
		}else {
			throw new KernelException( "Proměná v configu " . $value . " neexistuje.");
		}
	}
	
	/**
	 * Nastavi pro funkci iconv kodovani na z konstanty WEB_ENCODING
	 * @todo dodelat do configu funkce ktere budou nastavovat kodovani dle prani ne takto natvrdo
	 * @return exception / true
	 */
	public function setIconvEncoding()
	{
		if( iconv_set_encoding( 'internal_encoding'	, self::WEB_ENCODING )
			&& iconv_set_encoding( 'output_encoding'	, self::WEB_ENCODING )
			&& iconv_set_encoding( 'input_encoding'	, self::WEB_ENCODING )
		){
			return TRUE;
		}else {
			throw new KernelException( 'Nepodařilo se nastavit interní kódvání serveru.' );
		}
	}
	
}