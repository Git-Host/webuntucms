<?php

class Lang extends Object 
{
			// pole jazyku klicem je id jazyka
	private $langList,
			// pole jazyku kde klicem je symbol jazyka
			$symbolLangList,
			// objekt cache
			$cache;
	const CACHEID_LANG_LIST = 'lang_lang_list';
	const CACHEID_SYMBOL_LANG_LIST = 'lang_symbol_lang_list';
				
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
	
	public function getSymbolLangList()
	{
		if( NULL === $this->symbolLangList ){
			return $this->setSymbolLangList();
		}else {
			return $this->symbolLangList;
		}
	}
	
	private function setSymbolLangList()
	{
		$sql =  "SELECT id, symbol, country as name FROM " . BobrConf::DB_PREFIX ."lang";
		//$this->langList = $this->cache->sqlData( $sql, 'id');
		$this->langList = $this->cache->loadData( self::CACHEID_SYMBOL_LANG_LIST, $sql, 'symbol');
		return $this->langList;
	}
}