<?php
/**
 * Description of AdministrationCategory
 *
 * @author rbas
 */
class AdministrationCategory extends DataObject
{

	private $id = 0;

	private $routedynamicId = 0;

	private $descriptionId = 0;

	private $weight = 0;

    public function __construct($id = 0)
    {
        $importProperties = array('id' => 'id', 'routedynamic_id' => 'routedynamicId', 'description_id' => 'descriptionId', 'weight' => 'weight');
        $this->setImportProperties($importProperties);
        if (0 != $id) {
            $this->setId($id)->load();
        }
    }

    /**
     * Nacte data.
     *
     * @throws AdministrationCategoryException Pokud neni zadano id.
     * @return AdministrationCategory
     */
    public function load()
    {
        if (0 === $this->id) {
            require_once 'exception.php';
            throw new AdministrationCategoryException('Neni zadane id administracni kategorie kterou chces nacist.');
        }

        if (!$this->loadFromCache()) {
            $query = 'SELECT `id`, `routedynamic_id`, `description_id`, `weight`
                FROM `' . Config::DB_PREFIX . 'administrationcategory`
                WHERE id = ' . $this->id;
            $record = dibi::query($query)->fetch();
            $this->importRecord($record);
            //$this->saveToCache();
        }
        return $this;
    }

    /**
     * Vrati cachovi identifikator.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/modules/administration/' . $this->getClass() . '/' . $this->id;
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
	 * @return AdministrationCategory
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $routedynamicId
	 *
	 * @return integer
	 */
	public function getRoutedynamicId()
	{
		return $this->routedynamicId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $routedynamicId
	 *
	 * @param integer
	 * @return AdministrationCategory
	 */
	public function setRoutedynamicId($routedynamicId)
	{
		$this->routedynamicId = (integer)$routedynamicId;
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
	 * @return AdministrationCategory
	 */
	public function setDescriptionId($descriptionId)
	{
		$this->descriptionId = (integer)$descriptionId;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $weight
	 *
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
	 * @return AdministrationCategory
	 */
	public function setWeight($weight)
	{
		$this->weight = (integer)$weight;
		return $this;
	}

}