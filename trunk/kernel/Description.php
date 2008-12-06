<?php
/**
 * Description of Description
 *
 * @author rbas
 */
class Description extends Object
{
    private $id = 0;

	private $title = '';

	private $description = '';

    /**
     * Natahne do sebe data a vrati sebe.
     *
     * @param array $record
     * @return Description
     */
    public function importRecord(array $record)
    {
        $this->setId($record['id'])
            ->setTitle($record['title'])
            ->setDescription($record['description']);
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
	 * @return Description
	 */
	public function setId($id)
	{
		if (! is_numeric($id)) {
			throw new InvalidArgumentException('Promena $id musi byt datoveho typu integer.');
		}
		$this->id = (integer) $id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $title
	 *
	 * @param void
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * Nastavi hodnotu vlastnosti $title
	 *
	 * @param string
	 * @return Description
	 */
	public function setTitle($title)
	{
		if (! is_string($title) && ! is_numeric($title)) {
			throw new InvalidArgumentException('Promena $title musi byt datoveho typu string.');
		}
		$this->title = (string) $title;
		return $this;
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
	 * @return Description
	 */
	public function setDescription($description)
	{
		if (! is_string($description) && ! is_numeric($description)) {
			throw new InvalidArgumentException('Promena $description musi byt datoveho typu string.');
		}
		$this->description = (string) $description;
		return $this;
	}


}