<?php

class Module extends Object
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
		$this->functionsList = new FunctionsList;
		$this->functionsList->importRecord($functions);

		return $this;
	}
}