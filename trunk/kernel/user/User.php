<?php

class User extends Object
{
	private $id = 0;
	private $nick = '';
	private $email = '';
	private $pass = '';
	private $statusId	= 0;
	private $groupsList = array();

	const ANONYMOUS_USER_ID		= 2;

	public function __construct($id = NULL)
	{
		if(NULL === $id){
			$this->setAnonymous();
		}else{
			$this->loadUserInfo($id);
		}
	}

	private function setAnonymous()
	{
		$this->loadUserInfo(self::ANONYMOUS_USER_ID);
	}

	private function loadUserInfo($id)
	{
		$query = "SELECT `id`, `nick`, `pass`, `email`, `status_id`
			FROM `" . Config::DB_PREFIX . "users`
			WHERE `id` = " . $id;
		$this->importRecord(dibi::query($query)->fetch());
	}

	private function importRecord(array $record)
	{
		$this->id	=	$record['id'];
		$this->nick	=	$record['nick'];
		$this->email=	$record['email'];
		$this->pass	=	$record['pass'];
		$this->statusId	=	$record['status_id'];
	}

	/**
	 * Vrati v poli id vsech group ve kterych je uzivatel
	 *
	 * @param void
	 * @return array
	 */
	public function getGroupsId()
	{
		return array_keys($this->getGroupsList()->items);
	}

	/**
	 * Vrati objekty Group v poli indexovanych podle group id na ktere ma uzivatel pravo
	 *
	 * @param void
	 * @return mixed - pole objektu Group
	 */
	public function getGroups()
	{
		return $this->getGroupsList()->items;
	}

	/**
	 * Vrati vsechny objekty Modules v poli indexovanych podle group id na ktere ma uzivatel pravo
	 *
	 * @param void
	 * @return mixed - pole objektu Modules
	 */
	public function getModules()
	{
		foreach ($this->getGroupsList()->items as $id => $group) {
			$modules[$id] = $group->modules;
		}
		return $modules;
	}

	/**
	 * Vrati vsechny objekty FunctionModule v poli indexovanych podle module id na ktere ma uzivatel pravo
	 *
	 * @param void
	 * @return mixed - pole objektu FunctionModlue
	 */
	public function getFunctions()
	{
		$functions = array();
		foreach ($this->getModules() as $groupsModule) {
			foreach ($groupsModule as $moduleId => $module) {
				$functions[$moduleId] = $module->functions;
			}
		}
		return $functions;
	}

	/**
	 * Vrati pole commandu na ktere ma uzivatel pravo
	 *
	 * @param void
	 * @return array - command
	 */
	public function getCommands()
	{
		$moduleFunctions = $this->getFunctions();
		if (empty($moduleFunctions)) {
			throw new LogicException('Uzivatel nema zadne prava');
		}

		foreach ($moduleFunctions as $functions) {
			foreach($functions as $function){
				$command[] = $function->command;
			}
		}
		return $command;
	}

	public function getWebInstance()
	{
		foreach ($this->getGroups() as $groupId => $group) {
			foreach ($group->webInstance as $webInstance) {
				$webInstances[] = $webInstance->name;
			}
		}
		return $webInstances;
	}


	/**
	 * Vrati vlastnost groupList s objektama Group ve kterych je uzivatel
	 * pokud pole neni nastavene nastavi jej
	 *
	 * @param void
	 * @return mixed $groupsList - pole objektu Group
	 */
	public function getGroupsList()
	{
		if(empty($this->groupsList)){
			return $this->setGroupsList();
		}else{
			return $this->groupsList;
		}
	}

	/**
	 * Nastavi do vlastnosti groupList pole objektu Group, ktere se vztahuji k uzivately
	 *
	 * @param void
	 * @return mixed $groupList
	 */
	private function setGroupsList()
	{
		if($this->id < 1){
			throw new LogicException('Uzivatel neni inicializovan, nemuzu nacist skupiny.');
		}

		$groups = new GroupsList;
		$this->groupsList = $groups->loadGroupsByUserId($this->id);
		return $this->groupsList;
	}
}