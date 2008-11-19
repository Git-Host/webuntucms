<?php

class ModuleFunction extends Object
{
	private $id = 0;
	private $moduleId	= 0;
	private	$func	= 0;
	private $descriptionId	= 0;
	private	$author	= '';
	private $funcVersion	= '';
	private $bobrVersion	= '';
	private $webInstanceId	= 0;

	public function importRecord(array $record)
	{
		$this->id				=	$record['id'];
		$this->moduleId			=	$record['module_id'];
		$this->func				=	$record['func'];
		$this->descriptionId	=	$record['description_id'];
		$this->author			=	$record['author'];
		$this->funcVersion		=	$record['funcversion'];
		$this->bobrVersion		=	$record['bobrversion'];
		$this->webInstanceId	=	$record['webinstance_id'];

		return $this;
	}
}