<?php

class Bobr_User_Module extends Object
{
	private $id = 0;
	private $name = '';
	private	$statusId	= 0;
	private $descriptionId	= 0;

	private $functionsList = array();

	public function importRecord(array $info, array $functions)
	{
		$this->id				=	$info['id'];
		$this->name				=	$info['module'];
		$this->statusId			=	$info['status'];
		$this->descriptionId	=	$info['description_id'];

		$this->importFunctions($functions);

		return $this;
	}

	private function importFunctions(array $functions)
	{
		$this->functionsList = new Bobr_User_FunctionsList;
		$this->functionsList->importRecord($functions);

		return $this;
	}

	public function getFunctionIds()
	{
		return array_keys($this->functionsList->items);
	}

	public function getFunctions()
	{
		return $this->functionsList->items;
	}
}