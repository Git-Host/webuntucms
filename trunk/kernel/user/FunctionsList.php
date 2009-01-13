<?php

class Kernel_User_FunctionsList extends Object
{
	private $items = array();

	public function importRecord(array $record)
	{
		foreach($record as $id => $function){
			$this->items[$id] = new Kernel_User_ModuleFunction;
			$this->items[$id]->importRecord($function);
		}

		return $this;
	}
	public function getItems()
	{
		return $this->items;
	}
}