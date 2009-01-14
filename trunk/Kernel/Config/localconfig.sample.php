<?php

require_once __WEB_ROOT__ . '/lib/Tools.php';

require_once __WEB_ROOT__ . '/kernel/config/defaultconfig.php';

require_once __WEB_ROOT__ . '/kernel/config/' . Lib_Tools::getWebInstance() . 'config.php';
/**
 * Zakladni configuracni trida.
 * Trida je pretezovana.
 *
 * @author rbas
 */
class Kernel_Config_Config implements ArrayAccess
{

    /**
     * Prefix tabulek v databazi.
     *
     * @var string
     */
	const DB_PREFIX = 'bobr_';

    /**
     * Pole konfiguratoru a jejich hodnot.
     *
     * @var array
     */
	private $settings = array(
		// Spojeni na databazi
        'DBHOST'	=>	'localhost',
		'DBPORT'	=>	'5432',
		'DBNAME'	=>	'bobr_devel',
		'DBUSER'	=>	'postgres',
		'DBPASSWORD'=>	'******',
		'DBPERSISTENT'	=>	TRUE,
		'DBCONNECTIONNAME'	=>	'default',
		// Cesta k lokalnimu ulozisti dat
        'FILESTORAGEPATH'	=>	'/local/cache/',
        // Zapnuti|vypnuti cachovani
        'CACHEMODE' => TRUE,
        // Zapnuti|vypnuti debub modu
		'DEBUGMODE'	=>	TRUE,
        // Cesta k slozce share
		'SHARE'	=>	'/share',
        // Odkud se ma prebrat informace o langu.
        'REMOTELANGFROM' => 'config', // browser, uri, config
        // Defaultni symbol jazyka.
        'DEFAULTLANG'   => 'cs',
	);

    /**
     * Pokud konfigurator existuje v lokalnim configu vrati ho.
     * Pokud neexistuje zavola config spustene web instance a pokusi se ho najit tam.
     * (Pokud webInstancovy config v sobe nenajde hledany konfigurator, podiva se do defaultniho configu,
     * pokud ani tam nebude vyhodi se neodchycena vyjimka)
     *
     * @param string $name
     * @return mixed
     */
	public function offsetGet( $name )
	{
        // Zvetsime pismena.
		$value = strtoupper( $name );
        // Zjistime jestli konfigurator existuje.
		if(isset($this->settings[$value])){
			return $this->settings[ $value ];
		}else{
            // Konfigurator neexistuje, zavolame si webInstancovy config
			$configName = Lib_Tools::getWebInstance() .'Config';
			$instanceConfig = new $configName;
			return $instanceConfig[$name];
		}
	}

    /**
     * Zjistuje jestli dany konfigurator existuje.
     * (Tato funkce se spousti magicky staci volat jen isset($config->defaultLang).)
     *
     * @param string $name
     * @return boolean
     */
    public function offsetExists( $name )
	{
		$value = strtoupper( $name );
		return isset($this->settings[$name]);
	}

    /**
     * Vyhodi vyjimku, do lokalniho konfigu se nesmi zapisovat.
     *
     * @param string $name
     * @param mixed $value
     * @throws Exception
     */
	public function offsetSet( $name, $value )
	{
		throw new Exception('Nelze prirazovat hodnoty do lokalniho configu.');
	}

    /**
     * Vyhodi vyjimku, v lokalnim konfigu se neda nic odnastavit.
     *
     * @param string $name
     * @throws Exception
     */
	public function offsetUnset( $name )
	{
		throw new Exception('Nelze odnastavit hodnotu z lokalniho konfigu.');
	}

    /**
     * Magicke volani metody offsetGet
     *
     * @param string $name
     * @return mixed
     */
	public function __get($name)
	{
		return $this->offsetGet($name);
	}
}