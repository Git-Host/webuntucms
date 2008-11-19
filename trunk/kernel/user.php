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