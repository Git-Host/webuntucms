<?php
/**
 * Description of Description
 *
 * @author rbas
 */
class Bobr_Description extends Object
{
    private $id = 0;

	private $title = '';

	private $description = '';

    /**
     * Natahne do sebe data a vrati sebe.
     *
     * @param array $record
     * @return Bobr_Description
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
	 * @return Bobr_Description
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
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
	 * @return Bobr_Description
	 */
	public function setTitle($title)
	{
		$this->title = (string)$title;
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
	 * @return Bobr_Description
	 */
	public function setDescription($description)
	{
		$this->description = (string)$description;
		return $this;
	}


}