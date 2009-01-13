<?php

class Kernel_User_ModuleList extends Object
{
	private $items = array();

	/**
	 * K sestaveni mouleListu je potreba si nejdriva sahnout pro veskere funkce ktere jsou dostupne
	 */
	public function loadByGroupId($id)
	{
		$query = "SELECT mf.`id`, mf.`module_id`, mf.`func`, mf.`description_id`, mf.`author`, mf.`funcversion`, mf.`bobrversion`, mf.`webinstance_id`
			FROM `" . Kernel_Config_Config::DB_PREFIX . "module_functions` mf
			JOIN `" . Kernel_Config_Config::DB_PREFIX . "group_functions` gf ON gf.`module_function_id` = mf.`id`
			WHERE gf.`group_id` = " . $id . "
			ORDER BY `module_id`, `id`";
		$result = dibi::query($query)->fetchAssoc('module_id,id');

		$this->loadModules($result);

		return $this;
	}

	private function loadModules(array $functionsList)
	{
		if(empty($functionsList)){
			throw new LogicException('Uzivatel nema zadne prava k funkcim.');
		}

		foreach($functionsList as $moduleId => $functions){
			$modulesId[$moduleId] = $moduleId;
		}
		$query = "SELECT `id`, `module`, `status`, `description_id`
			FROM `" . Kernel_Config_Config::DB_PREFIX . "module`
			WHERE `id` IN (" . implode(',', $modulesId) . ")
			ORDER BY `id`";
		$result = dibi::query($query)->fetchAll();
		$this->importRecord($result, $functionsList);
	}

	private function importRecord(array $record, $functionsList)
	{
		foreach($record as $module){
			// @todo zatim to radim podle id nevim jestli se nebude hodit radit podle moduleName
			$this->items[$module['id']] = new Kernel_User_Module();
			$this->items[$module['id']]->importRecord($module, $functionsList[$module['id']]);
		}

		return $this;
	}

	public function getItems()
	{
		return $this->items;
	}
}