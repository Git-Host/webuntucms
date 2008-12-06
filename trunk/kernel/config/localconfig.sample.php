<?php

require_once __WEB_ROOT__ . '/lib/tools.php';

require_once __WEB_ROOT__ . '/kernel/config/defaultconfig.php';

require_once __WEB_ROOT__ . '/kernel/config/' . Tools::getWebInstance() . 'config.php';

class Config implements ArrayAccess
{

	const DB_PREFIX = 'bobr_';

	private $settings = array(
		'DBHOST'	=>	'localhost',
		'DBPORT'	=>	'5432',
		'DBNAME'	=>	'bobr_devel',
		'DBUSER'	=>	'postgres',
		'DBPASSWORD'=>	'******',
		'DBPERSISTENT'	=>	TRUE,
		'DBCONNECTIONNAME'	=>	'default',
		'FILESTORAGE'	=>	'/local/cache/',
        'CACHEMODE' => True,
		'DEBUGMODE'	=>	TRUE,
		'SHAREROOT'	=>	'/',
	);

	public function offsetExists( $name )
	{
		$value = strtoupper( $name );
		return isset($this->settings[$name]);
	}

	public function offsetGet( $name )
	{
		$value = strtoupper( $name );
		if(isset($this->settings[$value])){
			return $this->settings[ $value ];
		}else{
			$configName = Lib::getWebInstance() .'Config';
			$instanceConfig = new $configName;
			return $instanceConfig[$name];
		}
	}

	public function offsetSet( $name, $value )
	{
		throw new Exception('Nelze prirazovat hodnoty do lokalniho configu.');
	}

	public function offsetUnset( $name )
	{
		throw new Exception('Nelze odnastavit hodnotu z lokalniho konfigu.');
	}

	public function __get($name)
	{
		return $this->offsetGet($name);
	}
}