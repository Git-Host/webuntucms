<?php
/**
 * Trida na administrationCategory
 */
class Administration extends Object
{
	private $administrationCategoryList;
	
	private $cache;
	private static $instance = FALSE;
	 
	public static function getSingleton() {
		if(self::$instance === FALSE) {
			self::$instance = new Administration();
		}
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}
	
	public function getAdministrationCategoryList()
	{
		if( NULL === $this->administrationCategoryList ){
			return $this->setAdministrationCategoryList();
		}else{
			return $this->administrationCategoryList;
		}
	}
	
	private function setAdministrationCategoryList()
	{
		$sql = "SELECT id, description_id, pageid_id, url, weight FROM " . BobrConf::DB_PREFIX . "administrationcategory";
		$this->administrationCategoryList = $this->cache->sqlData( $sql, 'url');
		return $this->administrationCategoryList;
	}
}
?>