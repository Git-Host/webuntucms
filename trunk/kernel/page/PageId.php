<?php
/**
 * Description of PageId
 *
 * @author rbas
 */
class PageId extends DataObject
{

    /**
     * Unikatni identifikator stranky.
     *
     * @var integer
     */
	private $id = 0;

	/**
     * Vazba do druhe tabulky.
     *
     * @var integer
     */
    private $pageIdNode = 0;

	/**
     * Popis stranky, slouzi k administraci.
     *
     * @var string
     */
    private $description = '';

    /**
     * Cesta k css souboru.
     *
     * @var string
     */
    private $css = '';

    /**
     * Cesta k hlavni sablone.
     *
     * @var string
     */
    private $template = '';

    public function __construct($id = 0)
    {
        $importProperties = array(
            'id' => 'id',
            'pageid_node_id' => 'pageIdNode',
            'description' => 'description',
            'css' => 'css',
            'template' => 'template'
            );
        $this->setImportProperties($importProperties);

        if (0 != $id) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * Nacte data.
     */
    private function load()
    {
        if (!$this->loadFromCache()) {

            if (0 > $this->id) {
                throw new PageIdIAException('Neni zadano id pro PageId. Nemuze se nacist.');
            }
            
            $query = 'SELECT p.`pageid_node_id`, p.`description`,
                pn.`css`, pn.`template`
                FROM `' . Config::DB_PREFIX . 'pageid` p
                JOIN `' . Config::DB_PREFIX . 'pageid_node` pn ON p.`pageid_node_id` = pn.`id`
                WHERE p.`id` = ' . $this->id . ' LIMIT 1';
            $record = dibi::query($query)->fetch();

            if (empty($record)) {
                throw new PageIdException('PageId ' . $this->id . ' neexistuje.');
            }

            // Naimportujem data a ulozime do cache.
            $this->importRecord($record)
                ->saveToCache();
        }
    }

    /**
     * Vrati kesove id.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/page/' . $this->id . '/' . $this->getClass();
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
	 * @return PageId
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $pageIdNode
	 *
	 * @return integer
	 */
	public function getPageIdNode()
	{
		return $this->pageIdNode;
	}

	/**
	 * Nastavi hodnotu vlastnosti $pageIdNode
	 *
	 * @param integer
	 * @return PageId
	 */
	public function setPageIdNode($pageIdNode)
	{
		$this->pageIdNode = (integer)$pageIdNode;
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
	 * @return PageId
	 */
	public function setDescription($description)
	{
		$this->description = (string)$description;
		return $this;
	}

    /**
	 * Vrati hodnotu vlastnosti $css
	 *
	 * @return string
	 */
	public function getCss()
	{
		return $this->css;
	}

	/**
	 * Nastavi hodnotu vlastnosti $css
	 *
	 * @param string
	 * @return PageId
	 */
	public function setCss($css)
	{
		$this->css = (string)$css;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $template
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Nastavi hodnotu vlastnosti $template
	 *
	 * @param string
	 * @return PageId
	 */
	public function setTemplate($template)
	{
		$this->template = (string)$template;
		return $this;
	}

}