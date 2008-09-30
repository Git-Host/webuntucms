<?php

class Lang extends Object 
{
			// pole jazyku
	private $langList,
			// objekt cache
			$cache;
			
	private static $instance = FALSE;
	 
	public static function getSingleton() {
		if(self::$instance === FALSE) {
			self::$instance = new Lang();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}
	
	public function getLangList()
	{
		if( $this->langList ){
			return $this->langList;
		}else{
			return $this->setLangList();
		}
	}
	
	/**
	 * Nacte dosupne pole jazyku.
	 */
	private function setLangList()
	{
		$sql =  "SELECT id, symbol, country as name FROM " . BobrConf::DB_PREFIX ."lang";
		$this->langList = $this->cache->sqlData( $sql, 'id');
		return $this->langList;
	}
	
}