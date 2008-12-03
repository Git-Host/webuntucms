<?php

class Group extends Object
{
	private $id = 0;
	private $pid = 0;
	private $name = '';
	private $description = '';

	private $webInstanceList = array();

	private $modulesList = array();


	public function importRecord($record)
	{
		$this->id	=	$record['id'];
		$this->pid	=	$record['pid'];
		$this->name =	$record['title'];
		$this->description	=	$record['description'];
	}

	public function getWebInstanceList()
	{
		if(empty($this->webInstanceList)){
			return $this->setWebInstanceList();
		}else{
			return $this->webInstanceList;
		}
	}

	private function setWebInstanceList()
	{
		if($this->id < 1){
			throw new LogicException('Neni nastavena skupina, nemuzu nastavit jeji web instance.');
		}

		$this->webInstanceList = new WebInstanceList;
		$this->webInstanceList->loadByGroupId($this->id);
		return $this->webInstanceList;
	}

	public function getWebInstance()
	{
		return $this->getWebInstanceList()->items;
	}

	public function getModulesId()
	{
		return array_keys($this->getModulesList()->items);
	}

	public function getModules()
	{
		return $this->getModulesList()->items;
	}

	public function getModulesList()
	{
		if(empty($this->modulesList)){
			return $this->setModulesList();
		}else{
			return $this->modulesList;
		}
	}

	private function setModulesList()
	{
		if($this->id < 1){
			throw new LogicException('Neni nastavena skupina, nemuzu nastavit jeji moduly.');
		}

		$this->modulesList = new ModuleList;
		$this->modulesList->loadByGroupId($this->id);
		return $this->modulesList;
	}
}