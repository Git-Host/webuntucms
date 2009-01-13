<?php

class Kernel_User_WebInstanceList extends Object
{
	private $items = array();

	public function loadByGroupId($id)
	{
		$query = "SELECT w.`id`, w.`title`, w.`description`
			FROM `" . Kernel_Config_Config::DB_PREFIX . "webinstance` w
			JOIN `" . Kernel_Config_Config::DB_PREFIX . "group_webinstance` gw ON gw.`webinstance_id` = w.`id`
			WHERE gw.`group_id` = " . $id;
		$result = dibi::query($query)->fetchAssoc('id');
		$this->importRecord($result);
	}

	private function importRecord(array $record)
	{
		foreach($record as $id => $webInstance){
			$this->items[$id] = new Kernel_User_WebInstance;
			$this->items[$id]->importRecord($webInstance);
		}
	}

	public function getItems()
	{
		return $this->items;
	}
}