<?php

class Bobr_Module_Function_Colection extends Object
{
	private $items = array();

	public function importRecord(ArrayObject $record)
	{
		foreach($record as $id => $function){
			$this->items[$id] = new Bobr_User_ModuleFunction;
			$this->items[$id]->importRecord($function);
		}

		return $this;
	}
	public function getItems()
	{
		return $this->items;
	}
}