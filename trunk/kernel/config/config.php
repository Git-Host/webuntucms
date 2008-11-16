<?php

// Nacteme si lokalni konfiguraci
require_once __DIR__ . '/kernel/config/localconfig.php';

/**
 * V teto tride se definuji konstanty
 */
class BobrConf extends Settings
{
	const WEB_ENCODING			= 'utf-8';

	/*
	 * Pripojeni na databazi
	 */
	const DB_HOST				= LocalConfig::DB_HOST;
	const DB_PORT				= LocalConfig::DB_PORT;
	const DB_NAME				= LocalConfig::DB_NAME;
	const DB_USER				= LocalConfig::DB_USER;
	const DB_PASSWORD			= LocalConfig::DB_USER;
	const DB_PERSISTENT			= LocalConfig::DB_PERSISTENT;
	const DB_CONNECTION_NAME	= LocalConfig::DB_CONNECTION_NAME;
	const DB_PREFIX				= LocalConfig::DB_PREFIX;

	const CACHE_ROOT			= LocalConfig::CACHE_ROOT;
	const DEBUG_MODE			= LocalConfig::DEBUG_MODE;
	// Share url je zde mysleno ke slozce share. Vetsinou je url jen /
	const SHARE_URL				= LocalConfig::SHARE_URL;




	private static $instance = FALSE;

	// @todo tohle by se melo tahat z databaze
	private $settings = array(
		'WEB_TITLE'				=>	'BOBR COPR 2.0 DEVEL',
		// Pri dynamickem vytvareni titulku prevracet poradi
		'WEB_TITLE_REVERT'		=>	FALSE,
		'WEB_TITLE_SEPARATOR'	=>	'-',
		'WEB_META_KEYWORDS'		=>	'bobr, copr, punk, junk',
		'WEB_META_DESCRIPTION'	=>	'BOBR Pičo je nejlepšejší a pošle všechno do COPRu i DRUPAListy ;)',
		'WEB_AUTHOR'			=>	'BOBR COPR',
		'WEB_WEBMASTER'			=>	'BOBR PIČO',
		'WEB_COPYRIGHT'			=>	'BOBR',
		'WEB_FAVICON'			=>	'/share/kubuntu.png',
		'WEB_PAGEID_DEFAULT'	=>	'1',
		'WEB_ROOT'				=>	'/cs/',
		'WEB_LANG'				=>	'cs',
		// Pokud neni jazyk ulozev n session bere se z prohlizece
		'BROWSER_PREFERED_LANG'	=> FALSE,
		// Z obrazovat symbol jazyka (cs) v uri
		// POZOR kdyz se povoli tato funkcnost musi se symbol jazyka dat do WEB_ROOT a ADMIN_ROOT (/bobradmin/cs/)
		'LANG_SYMBOL_TO_URI'	=> TRUE,
		// -------------------------------------
		// Administrace default data
		'ADMIN_TITLE'				=>	'Jsi v administraci a nadpis je defaultni',
		// Pri dynamickem vytvareni titulku prevracet poradi
		'ADMIN_TITLE_REVERT'	=>	FALSE,
		'ADMIN_TITLE_SEPARATOR'	=>	'-',
		'ADMIN_META_KEYWORDS'	=>	'bobr, copr, punk, junk',
		'ADMIN_META_DESCRIPTION'=>	'BOBR Pičo je nejlepšejší a pošle všechno do COPRu i DRUPAListy ;)',
		'ADMIN_AUTHOR'			=>	'BOBR COPR',
		'ADMIN_WEBMASTER'		=>	'BOBR PIČO',
		'ADMIN_COPYRIGHT'		=>	'BOBR',
		'ADMIN_FAVICON'			=>	'/share/kubuntu.png',
		'ADMIN_ROOT'			=>	'/bobradmin/cs/',
		'ADMIN_LANG'			=>	'cs',
		'ADMIN_PAGEID_DEFAULT'	=>	'3',
		// Ostatni data
		'TIME_FORMAT'			=>	'd/m/Y - H:i',
		'FORBIDDEN_PAGE'		=>	'http://redtube.com',
		'LOGGING_CACHE'			=>	FALSE,

	);

	public static function getInstance() {
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