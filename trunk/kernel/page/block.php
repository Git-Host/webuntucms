<?php
class Block extends Object
{

	private $id = 0;

	private $moduleId = 0;

	private $containerId = 0;

	private $command = '';

	private $descriptionId = 0;

	private $weight = 0;

	public function importRecord(array $record)
	{
		$this->id = $this->setId($record['id']);
		$this->moduleId = $this->setModuleId($record['module_id']);
		$this->containerId = $this->setContainerId($record['container_id']);
		$this->command = $this->setCommand($record['command']);
		$this->descriptionId = $this->setDescriptionId($record['description_id']);
		$this->weight = $this->setWeight($record['weight']);

		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $id
	 *
	 * @param void
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
	 * @return integer
	 */
	private function setId($id)
	{
		if (! is_numeric($id)) {
			throw new InvalidArgumentException('Promena $id musi byt datoveho typu integer.');
		}
		$id = (int)$id;
		return $this->id = $id;
	}

	/**
	 * Vrati hodnotu vlastnosti $moduleId
	 *
	 * @param void
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
	 * @return integer
	 */
	private function setModuleId($moduleId)
	{
		if (! is_numeric($moduleId)) {
			throw new InvalidArgumentException('Promena $moduleId musi byt datoveho typu integer.');
		}
		$moduleId = (int)$moduleId;
		return $this->moduleId = $moduleId;
	}

	/**
	 * Vrati hodnotu vlastnosti $containerId
	 *
	 * @param void
	 * @return integer
	 */
	public function getContainerId()
	{
		return $this->containerId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $containerId
	 *
	 * @param integer
	 * @return integer
	 */
	private function setContainerId($containerId)
	{
		if (! is_numeric($containerId)) {
			throw new InvalidArgumentException('Promena $containerId musi byt datoveho typu integer.');
		}
		$containerId = (int)$containerId;
		return $this->containerId = $containerId;
	}

	/**
	 * Vrati hodnotu vlastnosti $command
	 *
	 * @param void
	 * @return string
	 */
	public function getCommand()
	{
		return $this->command;
	}

	/**
	 * Nastavi hodnotu vlastnosti $command
	 *
	 * @param string
	 * @return string
	 */
	private function setCommand($command)
	{
		if (is_array($command)) {
			throw new InvalidArgumentException('Promena $command musi byt datoveho typu string.');
		}
		$command = (string)$command;
		return $this->command = $command;
	}

	/**
	 * Vrati hodnotu vlastnosti $descriptionId
	 *
	 * @param void
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
	 * @return integer
	 */
	private function setDescriptionId($descriptionId)
	{
		if (! is_numeric($descriptionId)) {
			throw new InvalidArgumentException('Promena $descriptionId musi byt datoveho typu integer.');
		}
		$descriptionId = (int)$descriptionId;
		return $this->descriptionId = $descriptionId;
	}

	/**
	 * Vrati hodnotu vlastnosti $weight
	 *
	 * @param void
	 * @return integer
	 */
	public function getWeight()
	{
		return $this->weight;
	}

	/**
	 * Nastavi hodnotu vlastnosti $weight
	 *
	 * @param integer
	 * @return integer
	 */
	private function setWeight($weight)
	{
		if (! is_numeric($weight)) {
			throw new InvalidArgumentException('Promena $weight musi byt datoveho typu integer.');
		}
		$weight = (int)$weight;
		return $this->weight = $weight;
	}

}