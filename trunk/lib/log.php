<?php
class Log
{
			// pole souboru se kterymi pracovala cahce
	public static $fileCacheList = array();
	
	/*
	 * Prida do pole fileCacheList dalsi soubory
	 */
	public static function addCacheFile( $array )
	{
		self::$fileCacheList == array_merge( self::$fileCacheList, $array );
	}
	
	// musi se vytvorit instance
	public function __destruct()
	{
		echo 'pico';
		Ladenka::var_dumper( self::$fileCacheList );
	}
}