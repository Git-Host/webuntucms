<?php
/**
 * @todo Predelat tak aby to byl potomek tridy Object
 */
final class User
{
	private static $classname = __CLASS__;
	private static $instance = FALSE;
	private	$id			= FALSE,
			$nick		= FALSE,
			$userGroups	= FALSE,
			$email		= FALSE,
			$units		= FALSE,
			$unitgroups	= FALSE,
			$functions  = FALSE;
	/**
	 * Pokusi se zalogovat uzivatele, objekt se da volat jen jednou a pouze z instance Session_Kernel
	 * @return object User_Kernel
	 * @param $nick,$pass, Session_Kernel $obj
 	*/
	public static function getInstance($nick, $pass,  Session $obj)
	{
		if(self::$instance === FALSE) {
			return self::$instance = new self::$classname( $nick, $pass );
		} else {
			return FALSE;
		}
	}


	/**
	 * Konstruktor, ktery zprostredkuje data z databaze na zaklade $nick, $pass
	 * @return object User_Kernel
	 * @param $nick,$pass
 	*/
	private function __construct( $nick, $pass )
	{
		if(!empty( $nick ) && !empty( $pass )) {
			$result = array();

			$nick = mb_strtolower(API::handleTrash( $nick ));
			$pass = mb_strtolower(sha1(API::handleTrash( $pass )));

			$result = dibi::query("SELECT id,nick,email FROM " . BobrConf::DB_PREFIX . "users WHERE nick = '$nick' AND pass = '$pass' LIMIT 1");
			$result = $result->fetchAll();

			$this->id 		= (!empty( $result[0]['id']) ) 	? $result[0]['id']		: FALSE;
			$this->nick 	= (!empty( $result[0]['nick']) ) 	? $result[0]['nick'] 	: FALSE;
			$this->email	= (!empty( $result[0]['email']) ) 	? $result[0]['email'] 	: FALSE;

			if( FALSE != ($this->id || $this->nick || $this->email) ) {
				$this->userGroups = $this->getUserGroups();
			} else {
				return FALSE;
			}

			return $this;
		}
		else {
			return FALSE;
		}
	}

	/**
	 * Vraci vlastnost z
	 * @return array
	 */
	public function __get( $property ) {

		if ( property_exists($this,$property) ) {
			return $this->$property;
		} else {
			throw new KernelException("Vlastnost $property neexistuje");
		}
	}

	/**
	 * Vraci groupu do ktere uzivatel patri, jinak vraci FALSE (uzivatel nepatri do zadne groupy)
	 * @return array
	 */
	private function getUserGroups()
	{

		$result = dibi::query("SELECT group_id FROM " . BobrConf::DB_PREFIX . "user_groups WHERE user_id=%i ", $this->id);
		$result = $result->fetchAll();
		$this->groups = $result[0];
		$this->groups = is_array($this->groups) ? implode(',',$this->groups) : $this->groups;
	}


	/**
	 * Naplni vlastnost polem group do ktere uzivatel patri
	 *
	 */
	private function getGroups() {
		//nacte detaily / kategorie group(y), do kterych patrim
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "groups
								WHERE group_id IN(" . implode(",", $this->groups) . ") ");
		$this->groups = $result->fetchAssoc();
	}


	private function getGroupFunctions() {
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "group_functions
								WHERE group_id IN(" . implode(",", $this->userGroups) . ") ");
		$this->functions = $result->fetchAssoc();
	}


	/**
	 * Vraci pole unit do kterych uzivatel patri, jinak vraci FALSE (uzivatel nepatri do zadne unity)
	 * @return array
	 */
	private function getUnits()
	{
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "units WHERE user_id = %i", $this->id);
		$result = $result->fetchAll();
		return $result[0];
	}

}