<?php
class Validator extends Object
{
	private static $classname	= __CLASS__;
	private static $instance	= FALSE;

	private $session,
			$groupFunctions = array(),
			$groups = array(),
			$userGroups = array(),
			$modules = array();

	/* unity */
	private $interlockGroups = array();
	private $units = array();
	private $unitGroups = array();


	/**
	 * Vrati singleton instanci
	 * @return Object
	 */
	public static function getInstance( )
	{
		if( self::$instance === FALSE ) {
			self::$instance = new self::$classname();
		}
		return self::$instance;
	}

	/**
	 * Nacte groupy ze session a nastavi si pole funkci, na ktere ma uzivatel pravo
	 *
	 */
	private function __construct( )
	{
		$this->session = Session::getInstance();
		$this->groupFunctions = Module::getInstance()->setGroupFunctionsList( $this->session->getSession( 'user', 'groups' ) );
	}

	/**
	 * Zjistuje na ktere typy stranek uzivatel muze, v pripade neuspechu se nacita FORBIDDEN_PAGE z configu
	 *
	 * @param string $processType
	 * @return Boolean
	 */
	public function processAccess( $processType )
	{
		$result = dibi::query( "SELECT processtype FROM " . BobrConf::DB_PREFIX  . "group_access
								WHERE group_id IN (" . $this->session->getSession( 'user', 'groups') .")
								AND processtype = '" . $processType . "'");
		$result = $result->fetchAll();
		return (0 < count( $result )) ? TRUE : FALSE;
	}

	/**
	 * vraci privatni pole dostupnych funkci pro uzivatele user/anonym/...
	 *
	 * @return array
	 */
	public function getGroupFunctions() {
		return $this->groupFunctions;
	}

	private function getInterlockGroups() {
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "interlock_grups ");
		$this->interlockGroups = $result->fetchAssoc();
	}

	private function getUnits() {
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "units ");
		$this->units = $result->fetchAssoc();
	}

	private function getUnitGroups() {
		$result = dibi::query("SELECT * FROM " . BobrConf::DB_PREFIX . "unit_groups
								WHERE id IN(" . implode(",", $this->units) . ") ");
		$this->unitGroups = $result->fetchAssoc();
	}

	private function getUnitsOwner() {
	}

	private function getPageidUnitsGroups() {
	}

	private function interlock_groups() {
	}
}
