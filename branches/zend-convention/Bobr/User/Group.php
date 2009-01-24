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
	 * @var unknown_type
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

	public function __construct($id = 0)
	{
		$this->importProperties = array('id' => 'id', 'pid' => 'pid', 'title' => 'name', 'description' => 'description');
	}

	public function getCacheId()
	{
		return '/bobr/' . $this->getClass() . '/' . $this->id;
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