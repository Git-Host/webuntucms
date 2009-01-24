<?php
class Bobr_Module_Function extends Bobr_DataObject
{

	/**
	 * Id funkce.
	 *
	 * @var integer
	 */
	private $id = 0;

	/**
	 * Id modulu.
	 *
	 * @var integer.
	 */
	private $moduleId = 0;

	/**
	 * Command
	 *
	 * @var Bobr_Command
	 */
	private $command = NULL;

	/**
	 * Id lokalizovaneho popisu funkce.
	 *
	 * @var integer
	 */
	private $descriptionId = 0;

	/**
	 * Autor funkce.
	 *
	 * @var string
	 */
	private $author = '';

	/**
	 * Verze funkce.
	 *
	 * @var string
	 */
	private $functionVersion = '';

	/**
	 * Verze bobra.
	 *
	 * @var string
	 */
	private $bobrVersion = '';

	public function __construct()
	{
		$this->importProperties = array(
			'id' => 'id',
			'module_id' => 'moduleId',
			'func' => 'command',
			'description_id' => 'descriptionId',
			'author' => 'author',
			'func_version' => 'functionVersion',
			'bobr_version' => 'bobrVersion'
		);
	}

	public function getCacheId()
	{
		return '/bobr/' . $this->getClass() . '/' . $this->getId();
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
	 * @return Bobr_User_Group_Functions
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $moduleId
	 *
	 * @return integer
	 */
	public function getModuleId()
	{
		return $this->moduleId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $moduleId
	 *
	 * @param integer
	 * @return Bobr_User_Group_Functions
	 */
	public function setModuleId($moduleId)
	{
		$this->moduleId = (integer)$moduleId;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $command
	 *
	 * @return Bobr_Command
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * Nastavi hodnotu vlastnosti $command
	 *
	 * @param string
	 * @return Bobr_User_Group_Functions
	 */
	public function setCommand($command)
	{
		$this->command = new Bobr_Command($command);
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $descriptionId
	 *
	 * @return integer
	 */
	public function getDescriptionId()
	{
		return $this->descriptionId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $descriptionId
	 *
	 * @param integer
	 * @return Bobr_User_Group_Functions
	 */
	public function setDescriptionId($descriptionId)
	{
		$this->descriptionId = (integer)$descriptionId;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $author
	 *
	 * @return string
	 */
	public function getAuthor()
	{
		return $this->author;
	}

	/**
	 * Nastavi hodnotu vlastnosti $author
	 *
	 * @param string
	 * @return Bobr_User_Group_Functions
	 */
	public function setAuthor($author)
	{
		$this->author = (string)$author;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $functionVersion
	 *
	 * @return string
	 */
	public function getFunctionVersion()
	{
		return $this->functionVersion;
	}

	/**
	 * Nastavi hodnotu vlastnosti $functionVersion
	 *
	 * @param string
	 * @return Bobr_User_Group_Functions
	 */
	public function setFunctionVersion($functionVersion)
	{
		$this->functionVersion = (string)$functionVersion;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $bobrVersion
	 *
	 * @return string
	 */
	public function getBobrVersion()
	{
		return $this->bobrVersion;
	}

	/**
	 * Nastavi hodnotu vlastnosti $bobrVersion
	 *
	 * @param string
	 * @return Bobr_User_Group_Functions
	 */
	public function setBobrVersion($bobrVersion)
	{
		$this->bobrVersion = (string)$bobrVersion;
		return $this;
	}

}