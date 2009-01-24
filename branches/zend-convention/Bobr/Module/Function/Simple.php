<?php
class Bobr_Module_Function_Simple extends Object
{
	private $id = 0;
	private $command = NULL;
	
	public function importRecord(ArrayObject $record)
	{
		$this->id = $record['id'];
		$this->command = new Bobr_Command($record['func']);
	}
}
