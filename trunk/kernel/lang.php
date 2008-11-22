<?php
/**
 * Data object lang
 */
class Lang extends Object
{

	private $id = 0;

	private $symbol = '';

	private $name = '';

	private $langList = array();

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
	 * @return void
	 */
	private function setId($id)
	{
		if (! is_numeric($id)) {
			throw new InvalidArgumentException('Promena $id musi byt datoveho typu integer.');
		}
		$id = (int)$id;
		$this->id = $id;
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
	 * @return void
	 */
	private function setSymbol($symbol)
	{
		if (! is_string($symbol) && ! is_numeric($symbol)) {
			throw new InvalidArgumentException('Promena $symbol musi byt datoveho typu string.');
		}
		$symbol = (string)$symbol;
		$this->symbol = $symbol;
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
	 * @return void
	 */
	private function setName($name)
	{
		if (! is_string($name) && ! is_numeric($name)) {
			throw new InvalidArgumentException('Promena $name musi byt datoveho typu string.');
		}
		$name = (string)$name;
		$this->name = $name;
	}

	/**
	 * Vrati hodnotu vlastnosti $langList
	 *
	 * @param void
	 * @return array
	 */
	public function getLangList()
	{
		return $this->langList;
	}

	/**
	 * Nastavi hodnotu vlastnosti $langList
	 *
	 * @param array
	 * @return void
	 */
	private function setLangList($langList)
	{
		if (! is_array($langList)) {
			throw new InvalidArgumentException('Promena $langList musi byt datoveho typu array.');
		}
		$langList = (array)$langList;
		$this->langList = $langList;
	}

}