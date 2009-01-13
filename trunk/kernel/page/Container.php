<?php
/**
 * Nese v sobe hlavickove udaje o kontejnerech a obsahuje v sobe kolekci
 * objektu Block.
 *
 * @author rbas
 */
class Kernel_Page_Container extends Kernel_Colection
{

	/**
     * Unikatni ciselny identifikator kontejneru.
     *
     * @var integer
     */
    private $id = 0;

	/**
     * Unikatni slovni identifikator kontejneru.
     * Tento string je ident musi tedy byt bez diakritiky a podobnej veci...
     *
     * @var string
     */
    private $name = '';

	/**
     * Popis kontejneru. Slouzi pro administraci.
     *
     * @var string
     */
    private $description = '';

    /**
     * Nastavi importovaci pole a tridu.
     * V pripade ze je zadana vstupni hodnota a neni 0 zavola metodu loadContainer.
     *
     * @param integer $id 
     */
    public function __construct($id = 0)
    {
        $importProperties = array('id' => 'id', 'name' => 'name', 'description' => 'description');
        $this->setImportProperties($importProperties);
        $this->setColectionClass('Kernel_Page_Block');

        if (0 != $id) {
            $this->setId($id);
            $this->loadContainer();
        }
    }

    /**
     * Nacte blocky a naimportuje je do sebe.
     * Tento dotaz se nekesuje. Kes se provede v objektu ContainerColection.
     *
     *
     * @param string $blockIds Cisla blocku ktere chcem nacist oddelene carkou.
     * @throws Kernel_Page_ContainerException Pokud neexistuje ani jeden z blcku.
     * @return Kernel_Page_Container
     */
    public function loadBlockByIds($blockIds)
    {
        $query = 'SELECT `id`, `command`, `description`
            FROM `' . Kernel_Config_Config::DB_PREFIX . 'block`
            WHERE `id` IN (' . $blockIds . ')';
        $record = dibi::query($query)->fetchAll();

        if (empty($record)) {
            throw new Kernel_Page_ContainerException('Blocky ' . $blockIds . ' neexistuji.');
        }

        $this->importColection($record);

        return $this;
    }

    /**
     * Nacte container z databaze.
     *
     * @throws InvalidArgumentException Pokud neni vyplnena vlastnost id.
     * @throws Kernel_Page_ContainerException Pokud nenajde zadne data.
     * @return Kernel_Page_Container
     */
    public function loadContainer()
    {
        if (0 > $this->Id) {
                throw new Kernel_Page_ContainerIAException('Neni zadano id kontejneru ktery se ma nacist.');
        }

        
        $query = 'SELECT `name`, `description` FROM `' . Kernel_Config_Config::DB_PREFIX . 'container` WHERE id = ' . $this->id . ' LIMT 1';
        $record = dibi::query($query)->fetch();

        if (empty($record)) {
            throw new Kernel_Page_ContainerException('Container id ' . $this->id . ' neexistuje.');
        }


        return $this;
    }

    /**
     * Vrati cach identifikator.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/page/container/' . $this->id;
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
	 * @return Kernel_Page_Container
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
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
	 * @return Kernel_Page_Container
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
	 * @return Kernel_Page_Container
	 */
	public function setDescription($description)
	{
		$this->description = (string)$description;
		return $this;
	}

}