<?php
/**
 * Module slouzi pro praci s tabulkou module
 *
 */
class Module extends Object
{
	// pole modulu
	private $allModuleList;
	// pole dynamickych modulu
	private $dynamicModuleList;
	// pole group function modulu
	private $groupFunctionsList = array();
	// objekt cache
	private $cache;

	const CACHEID_ALL_MODULE_LIST			= 'module_all_module_list';
	const CACHEID_DYNAMIC_MODULE_LIST	= 'module_dynamic_module_list';
	const CACHEID_GROUP_FUNCTIONS_LIST	= 'module_group_function_list';

	private static $instance = FALSE;

	public static function getInstance()
	{
		if(self::$instance === FALSE) {
			self::$instance = new Module();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}

	/**
	 * Zjistuje jestli se jedna o modul
	 *
	 * @todo prochazet pole modulu a ne delat dalsi select
	 *
	 * $moduleName string - Nazev modulu
	 * @return  bool
	 */
	public static function isModule($moduleName)
	{
		if(isset($moduleName[1])) {
			$result = dibi::query(
				"SELECT module
				FROM ".BobrConf::DB_PREFIX."module
				WHERE module = '".$moduleName[1]."'"
			);
			return $result->fetchAll() ? TRUE : FALSE;
		} else {
			return FALSE;
		}
	}

	public function getAllModuleList()
	{
		if(NULL === $this->allModuleList) {
			$this->allModuleList = $this->setModuleList();
			return $this->allModuleList;
		} else {
			return $this->allModuleList;
		}
	}

	private function setAllModuleList()
	{
		$sql =
			"SELECT m.id, m.module, m.description_id, m.administrationcategory_id,
			mf.id as function_id,	mf.func, m.isdynamic, mf.pageid_id, mf.description_id as function_description_id
			FROM ".BobrConf::DB_PREFIX."module m
			JOIN ".BobrConf::DB_PREFIX."module_functions mf ON m.id = mf.module_id
			WHERE status = 1";
		//$this->moduleList = $this->cache->sqlData($sql, 'hash');
		$this->allModuleList = $this->cache->loadData(self::CACHEID_MODULE_LIST, $sql, 'module, mf.function_id');
		return $this->allModuleList;
	}

	public function getDynamicModuleList()
	{
		if(NULL === $this->dynamicModuleList) {
			throw new InvalidArgumentException('Neni nacteno pole s dynamickyma modulama.');
		} else {
			return $this->dynamicModuleList;
		}
	}

	/**
	 * @todo Pridelat do module_functions page_id a lang v pripade dynamickych modulu
	 * neni odkud tyto informace tahat
	 */
	public function setDynamicModuleList($webInstanceId)
	{
		$sql =
			"SELECT mf.func, dm.lang_id, dm.page_id, m.module
			FROM bobr_module_functions mf
			JOIN bobr_dynamicmodule dm ON dm.module_functions_id = mf.id
			JOIN bobr_module m ON mf.module_id = m.id
			WHERE mf.webinstance_id = ".$webInstanceId;
		$this->dynamicModuleList = $this->cache->loadData(self::CACHEID_DYNAMIC_MODULE_LIST.$webInstanceId, $sql, 'module');
		return $this->validateDynamicModuleList();
	}

	private function validateDynamicModuleList()
	{
		$dynamicModuleList = array();
		foreach($this->dynamicModuleList as $key => $module) {
			if(isset($this->groupFunctionsList[$key])) {
				$dynamicModuleList[$key] = $this->dynamicModuleList[$key];
			}
		}
		return $dynamicModuleList;
	}

	public function getGroupFunctionsList()
	{
		if(empty($this->groupFunctionsList)) {
			throw new InvalidArgumentException('Neznam skupinu pro kterou chces pole metod.');
		} else {
			return $this->groupFunctionsList;
		}
	}

	public function setGroupFunctionsList($groupsId)
	{
		$sql =
			"SELECT gf.group_id, gf.module_id, gf.module_function_id,
			mf.hash, mf.func , mf.administration, mf.description_id,
			m.module
			FROM ".BobrConf::DB_PREFIX."group_functions gf
			JOIN ".BobrConf::DB_PREFIX."module_functions mf ON mf.id = gf.module_function_id
			JOIN ".BobrConf::DB_PREFIX."module m ON mf.module_id = m.id
			WHERE group_id IN(".$groupsId.")
			ORDER BY module_id, module_function_id, group_id";
		//$this->groupFunctionsList = $this->cache->sqlData($sql, 'hash');
		$this->groupFunctionsList = $this->cache->loadData(self::CACHEID_GROUP_FUNCTIONS_LIST.Api::cacheId($groupsId ), $sql, 'module,module_function_id');
		return $this->groupFunctionsList;
	}


}