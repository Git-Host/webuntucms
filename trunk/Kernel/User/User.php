<?php

class Kernel_User_User extends Object
{
	private $id = 0;
	private $nick = '';
	private $email = '';
	private $pass = '';
	private $statusId	= 0;
	private $groupsList = array();

	const ANONYMOUS_USER_ID = 2;

	public function __construct($id = 0)
	{
		if(0 != $id){
			$this->loadUserInfo($id);
		}
	}

    /**
     * Nacte uzivatele podle nicku
     *
     * @param string $nick
     * @return User
     */
    public function loadByNick($nick)
    {
        $this->setNick($nick);

        $query = "SELECT `id`, `pass`, `email`, `status_id`
            FROM `" . Kernel_Config_Config::DB_PREFIX . "users`
            WHERE nick = '" . $this->nick . "'
            LIMIT 1";
        $data = dibi::query($query)->fetch();
        if (empty($data)) {
            throw new Kernel_User_UserNotExistException('Uzivatelske jmeno ' . $this->nick . ' neexistuje.');
        }
        $this->importRecord($data);
        
        return $this;
    }

	/**
     * Nastavi anonymouse.
     * 
     * @return User
     */
    public function setAnonymous()
	{
		$this->loadUserInfo(self::ANONYMOUS_USER_ID);
        return $this;
	}

	/**
     * Nacte zakladni uzivatelske udaje podle id.
     *
     * @param integer $id 
     */
    private function loadUserInfo($id)
	{
        $this->setId($id);
		$query = "SELECT `id`, `nick`, `pass`, `email`, `status_id`
			FROM `" . Kernel_Config_Config::DB_PREFIX . "users`
			WHERE `id` = " . $this->id;
        $data = dibi::query($query)->fetch();
        if (empty($data)) {
            throw new Kernel_User_UserNotExistException('Uzivatel s id ' . $this->id . ' neexistuje.');
        }
		$this->importRecord($data);
	}

	private function importRecord(array $record)
	{
		if (isset($record['id'])) {
            $this->setId($record['id']);
        }
        if (isset($record['nick'])) {
            $this->setNick($record['nick']);
        }

        if (isset($record['email'])) {
            $this->setEmail($record['email']);
        }

        if (isset($record['pass'])) {
            $this->setPass($record['pass']);
        }

        if (isset($record['status_id'])) {
            $this->setStatusId($record['status_id']);
        }
	}

    /**
     * Pokud je v session validni uzivatel a neni to anonymnous vraci true.
     *
     * @return boolean
     */
    public static function isLoged()
    {
        $userValidator = new Kernel_User_UserValidator;
        $user = Kernel_Session::getInstance()->user;
        if (TRUE === $userValidator->validate() && (self::ANONYMOUS_USER_ID != $user->id)) {
            return TRUE;
        } else {
            return FALSE;
        }
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
     * Vrati pole kde jako index jsou ID funkci.
     *
     * @return array
     */
    public function getFunctionsId()
    {
        $id = array();
        foreach ($this->getFunctions() as $module) {
            $id = array_merge($id, array_keys($module));
        }
        $id = array_flip($id);
        return $id;
    }
	/**
	 * Vrati pole commandu na ktere ma uzivatel pravo jako klic je functionId
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
				$command[$function->id] = $function->command;
			}
		}
		return $command;
	}

	public function getWebInstance()
	{
		foreach ($this->getGroups() as $groupId => $group) {
			foreach ($group->webInstance as $id => $webInstance) {
				$webInstances[$id] = $webInstance->name;
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

		$groups = new Kernel_User_GroupsList;
		$this->groupsList = $groups->loadGroupsByUserId($this->id);
		return $this->groupsList;
	}


    /**
     * Vrati hodnotu vlastnosti id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Nastavi hodnotu vlastnosti id
     *
     * @param integer $id
     * @return User
     */
    private function setId($id)
    {
        $this->id = (integer)$id;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Nastavi vlastnost nick.
     *
     * @param string $nick
     * @return User
     */
    public function setNick($nick)
    {
        if (FALSE === is_array($nick)) {
            $this->nick = $nick;
        } else {
            throw new InvalidArgumentException('Uzivatelsky nick musi byt ve tvaru varchar.');
        }

        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Nastavi hodnotu vlastnosti email
     *
     * @todo validovat jestli se jedna o platny email
     * @param string $email
     * @return User
     */
    private function setEmail($email)
    {
        $this->email = (string)$email;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti pass
     *
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * Nastavi hodnotu vlastnosti pass
     *
     * @param string $pass
     * @return User
     */
    private function setPass($pass)
    {
        $this->pass = trim($pass);
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti statusId
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Nastavi hodnotu vlastnosti statusId
     *
     * @param integer $statusId
     * @return User
     */
    private function setStatusId($statusId)
    {
        $this->statusId = (integer)$statusId;
        return $this;
    }
}