<?php
/**
 * Data object lang
 */
class Bobr_Lang extends Object
{

    /**
     * Id langu.
     *
     * @var integer
     */
	private $id = 0;

	/**
     * Symbol jazyka (cs,en,de)
     *
     * @var string
     */
    private $symbol = '';

    /**
     * Nazev jazyka.
     *
     * @var string
     */
	private $name = '';

    public function importRecord(array $record)
    {
        $this->setId($record['id'])
        ->setSymbol($record['symbol'])
        ->setName($record['country']);
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
	 * @return Bobr_Lang
	 */
	private function setId($id)
	{
		$this->id = (integer) $id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $symbol
	 *
	 * @param void
	 * @return string
	 */
	public function getSymbol()
	{
		return $this->symbol;
	}

	/**
	 * Nastavi hodnotu vlastnosti $symbol
	 *
	 * @param string
	 * @return Bobr_Lang
	 */
	private function setSymbol($symbol)
	{
		$this->symbol = (string) $symbol;
		return $this;
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
	 * @return Bobr_Lang
	 */
	private function setName($name)
	{
		$this->name = (string) $name;
		return $this;
	}

}