<?php
/**
 * Module slouzi pro praci s tabulkou module
 *
 */
class Module extends Object 
{
			// pole modulu
	private $moduleList,
			// pole dynamickych modulu
			$dynamicModuleList,
			// pole group function modulu
			$groupFunctionsList,
			// objekt cache
			$cache;
			
	const CACHEID_MODULE_LIST			= 'module_module_list';
	const CACHEID_DYNAMIC_MODULE_LIST	= 'module_dynamic_module_list';
	const CACHEID_GROUP_FUNCTIONS_LIST	= 'module_group_function_list';
	
	private static $instance = FALSE;
	 
	public static function getSingleton() {
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
	public static function isModule( $moduleName )
	{
		if( isset( $moduleName[1]) ){
			$result = dibi::query("SELECT module FROM " . BobrConf::DB_PREFIX . "module WHERE module = '$moduleName[1]'");
			return $result->fetchAll()  ? TRUE : FALSE;
		}else{
			return FALSE;
		}
	}
	
	public function getModuleList()
	{
		if( NULL === $this->moduleList ){
			$this->moduleList = $this->setModuleList();
			return $this->moduleList;
		}else {
			return $this->moduleList;
		}
	}
	private function setModuleList()
	{
		$sql = "SELECT m.id, m.module, m.description_id, m.administrationcategory_id, mf.hash, mf.func, m.isdynamic, mf.description_id as function_description_id
				FROM " . BobrConf::DB_PREFIX . "module m
				JOIN " . BobrConf::DB_PREFIX . "module_functions mf ON m.id = mf.module_id
				WHERE status = 1";
		//$this->moduleList = $this->cache->sqlData ( $sql, 'hash');
		$this->moduleList = $this->cache->loadData( self::CACHEID_MODULE_LIST, $sql, 'hash');
		return $this->moduleList;
	}
	
	public function getDynamicModuleList()
	{
		if( NULL === $this->dynamicModuleList ){
			$this->dynamicModuleList = $this->setDynamicModuleList();
			return $this->dynamicModuleList;
		}else{
			return $this->dynamicModuleList;
		}
	}
	
	/**
	 * @todo Pridelat do module_functions page_id a lang v pripade dynamickych modulu
	 * neni odkud tyto informace tahat
	 */
	private function setDynamicModuleList()
	{
		$sql = "SELECT mf.hash, mf.func FROM bobr_module_functions mf 
				JOIN bobr_dynamicmodule dm ON dm.module_functions_id = mf.id
				WHERE mf.administration = 'f'";
		//$this->dynamicModuleList = $this->cache->sqlData( $sql, 'hash');
		$this->dynamicModuleList = $this->cache->loadData( self::CACHEID_DYNAMIC_MODULE_LIST, $sql, 'hash');
		return $this->dynamicModuleList;
	}
	
	public function getGroupFunctionsList()
	{
		return $this->groupFunctionsList;
	}
	
	public function setGroupFunctionsList( $groupsId )
	{
		$sql = "SELECT gf.group_id, gf.module_id, gf.module_function_id, mf.hash, mf.func , mf.administration, mf.description_id
				FROM " . BobrConf::DB_PREFIX  . "group_functions gf
				JOIN " . BobrConf::DB_PREFIX  . "module_functions mf ON mf.id = gf.module_function_id
				WHERE group_id  IN(" . $groupsId . ")
				ORDER BY module_id, module_function_id, group_id";
		//$this->groupFunctionsList = $this->cache->sqlData( $sql, 'hash');
		$this->groupFunctionsList = $this->cache->loadData( self::CACHEID_GROUP_FUNCTIONS_LIST . Api::cacheId( $groupsId ), $sql, 'hash' );
		return $this->groupFunctionsList;
	}
	
	
}