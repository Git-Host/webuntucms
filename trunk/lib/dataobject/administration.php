<?php
/**
 * Trida na administrationCategory
 */
class Administration extends Object
{
	private $administrationCategoryList;

	private $cache;
	private static $instance = FALSE;

	const CACHEID_ADMINSTRATION_CATEGORY_LIST = 'administration_administration_category_list';

	public static function getInstance() {
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
		if(NULL === $this->administrationCategoryList) {
			return $this->setAdministrationCategoryList();
		} else {
			return $this->administrationCategoryList;
		}
	}

	private function setAdministrationCategoryList()
	{
		$sql =
			"SELECT id, description_id, pageid_id, url, weight
			FROM ".BobrConf::DB_PREFIX."administrationcategory";
		//$this->administrationCategoryList = $this->cache->sqlData($sql, 'url');
		$this->administrationCategoryList = $this->cache->loadData(
			self::CACHEID_ADMINSTRATION_CATEGORY_LIST,
			$sql,
			'url'
		);
		return $this->administrationCategoryList;
	}
}
?>