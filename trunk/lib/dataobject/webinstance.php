<?php
class WebInstance extends Object
{
	private
		$webInstanceList = '',
		$cache = NULL;
	const CACHEID_WEBINSTANCE_LIST = 'webinstance_list';

	private static $instance = FALSE;

	public static function getInstance() {
		if(self::$instance === FALSE) {
			self::$instance = new WebInstance();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}

	public function getWebInstanceList()
	{
		if( empty( $this->webInstanceList ) ){
			return $this->setWebInstanceList();
		}else{
			return $this->webInstanceList;
		}
	}

	protected function setWebInstanceList()
	{
		$sql =  "SELECT id, title, description FROM " . BobrConf::DB_PREFIX ."webinstance";
		$this->webInstanceList = $this->cache->loadData( self::CACHEID_WEBINSTANCE_LIST, $sql, 'title');
		return $this->webInstanceList;
	}
}
?>