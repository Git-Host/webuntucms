<?php
/**
 * Container je uloziste pro Blocky
 *
 * @author rbas
 */
class Container extends Object
{

    /**
     * Id Containeru.
     *
     * @var integer
     */
	private $id = 0;

	/**
     * Jmeno kontejneru. Jmeno musi byt ve tvaru jako URI.
     *
     * @var string
     */
    private $name = '';

    /**
     * Popis Containeru.
     * Popis slouzi predevsim administratorovi webu, proto se nepreklada.
     *
     * @var string
     */
	private $description = '';

	/**
     * Pole objektu Block.
     *
     * @var array
     */
    private $blocksList = array();

	/**
     * Naimportuje do sebe $record.
     *
     * @param array $record
     * @return Container
     */
    public function importRecord(array $record)
	{
		$this->id = $this->setId($record['id']);
		$this->name = $this->setName($record['title']);
		$this->description = $this->setDescription($record['description']);

		return $this;
	}

    /**
     * Naimportuje do vlastnosti $blocksList objekt BlockList.
     *
     * @param array $blocks
     */
	public function importBlocks(array $blocks)
	{
		$this->blocksList = new BlocksList;
		$this->blocksList->importBlocks($blocks);
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
	 * Vrati hodnotu vlastnosti $name
	 *
	 * @param void
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
	 * @return string
	 */
	private function setName($name)
	{
		if (! is_string($name) && ! is_numeric($name)) {
			throw new InvalidArgumentException('Promena $name musi byt datoveho typu string.');
		}
		$name = (string)$name;
		return $this->name = $name;
	}

	/**
	 * Vrati hodnotu vlastnosti $description
	 *
	 * @param void
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
	 * @return string
	 */
	private function setDescription($description)
	{
		if (! is_string($description) && ! is_numeric($description)) {
			throw new InvalidArgumentException('Promena $description musi byt datoveho typu string.');
		}
		$description = (string)$description;
		return $this->description = $description;
	}

}