<?php

class Lang extends Object 
{
			// pole jazyku
	private $langList,
			// objekt cache
			$cache;
	const CACHEID_LANG_LIST = 'lang_lang_list';
				
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
		//$this->langList = $this->cache->sqlData( $sql, 'id');
		$this->langList = $this->cache->loadData( self::CACHEID_LANG_LIST, $sql, 'id');
		return $this->langList;
	}
	
}