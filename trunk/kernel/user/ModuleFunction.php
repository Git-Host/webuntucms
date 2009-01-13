<?php

class Kernel_User_ModuleFunction extends Object
{
	private $id = 0;
	private $moduleId	= 0;
	private	$command	= 0;
	private $descriptionId	= 0;
	private	$author	= '';
	private $funcVersion	= '';
	private $bobrVersion	= '';
	private $webInstanceId	= 0;

	public function importRecord(array $record)
	{
		$this->id				=	$record['id'];
		$this->moduleId			=	$record['module_id'];
		$this->command			=	$record['func'];
		$this->descriptionId	=	$record['description_id'];
		$this->author			=	$record['author'];
		$this->funcVersion		=	$record['funcversion'];
		$this->bobrVersion		=	$record['bobrversion'];
		$this->webInstanceId	=	$record['webinstance_id'];

		return $this;
	}

	public function getCommand()
	{
		return $this->command;
	}

    public function getId()
    {
        return $this->id;
    }
}