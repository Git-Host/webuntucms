<?php

class Bobr_User_Group extends Bobr_DataObject
{

	/**
	 * Id skupiny.
	 *
	 * @var integer
	 */
	private $id = 0;

	/**
	 * Id rodicovske skupiny.
	 *
	 * @var integer
	 */
	private $pid = 0;

	/**
	 * Nazev skupiny.
	 *
	 * @var string
	 */
	private $name = '';

	/**
	 * Popis skupiny.
	 * Slouzi pouze pro administracni ucely.
	 *
	 * @var string
	 */
	private $description = '';
	/**
	 * Nastavni zakladni vlastnosti.
	 * 
	 * @param integer $id
	 */
	public function __construct($id = 0)
	{
		if ($id > 0) {
			$thsi->setId($id);
		}
	}
	
	/**
	 * Nacte udaje o skupine.
	 * 
	 * @return Bobr_User_Group
	 */
	public function load()
	{
		if ($this->id > 0) {
			throw new Bobr_User_Group_IAException('Neni vyplneno id skupiny, nemuzu nacist data.');
		}
		
		if (!$this->loadFromCache()) {
			$query = 'SELECT `id`, `pid`, `name`, `description`
				FROM `' . Config::DB_PREFIX . 'groups`
				WHERE `id` = ' . $this->id;
			$record = dibi::query($query)->fetch();
			if (empty($record)) {
				return NULL;
			} else {
				$this->importRecord($record)->saveToCache();
			}
		}
		return $this;
	}
	

	/**
	 * (non-PHPdoc)
	 * @see zend-convention/Bobr/Bobr_DataObjectInterface#getCacheId()
	 */
	public function getCacheId()
	{
		return '/bobr/' . $this->getClass() . '/' . $this->id;
	}
	/**
	 * Vrati pole pro import nebo export dat v zavislosti na $type.
	 * 
	 * @param string $type
	 * @return array
	 */
	protected function getRecordMap($type)
	{
		static $map = array(
			'id' => 'id', 
			'pid' => 'pid', 
			'title' => 'name', 
			'description' => 'description'
		);
		return $this->returnMap($type, $map);
	}

	/**
	 * Vrati hodnotu vlastnosti $id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Nastavi hodnotu vlastnosti $id
	 *
	 * @param integer
	 * @return Bobr_User_Group
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $pid
	 *
	 * @return integer
	 */
	public function getPid()
	{
		return $this->pid;
	}

	/**
	 * Nastavi hodnotu vlastnosti $pid
	 *
	 * @param integer
	 * @return Bobr_User_Group
	 */
	public function setPid($pid)
	{
		$this->pid = (integer)$pid;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $name
	 *
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Nastavi hodnotu vlastnosti $name
	 *
	 * @param string
	 * @return Bobr_User_Group
	 */
	public function setName($name)
	{
		$this->name = (string)$name;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Nastavi hodnotu vlastnosti $description
	 *
	 * @param string
	 * @return Bobr_User_Group
	 */
	public function setDescription($description)
	{
		$this->description = (string)$description;
		return $this;
	}

}