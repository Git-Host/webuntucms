<?php

class WebInstanceList extends Object
{
	private $items = array();

	public function loadByGroupId($id)
	{
		$query = "SELECT w.`id`, w.`title`, w.`description`
			FROM `" . Config::DB_PREFIX . "webinstance` w
			JOIN `" . Config::DB_PREFIX . "group_webinstance` gw ON gw.`webinstance_id` = w.`id`
			WHERE gw.`group_id` = " . $id;
		$result = dibi::query($query)->fetchAssoc('title');
		$this->importRecord($result);
	}

	private function importRecord(array $record)
	{
		foreach($record as $title => $webInstance){
			$this->items[$title] = new WebInstance;
			$this->items[$title]->importRecord($webInstance);
		}
	}
}