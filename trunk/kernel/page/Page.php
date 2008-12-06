<?php
/**
 * Obsahuje v sobe veskere objekty potrebne pro vypsani stranky
 */
class Page extends DataObject
{

	/**
     * Id stranky.
     *
     * @var integer
     */
    private $id = 0;

	/**
     * Cesta k template.
     *
     * @var string
     */
    private $template = '';

	/**
     * Id pageNodeId. 
     *
     * @var integer
     */
    private $pageNodeId = 0;

	/**
     * Idecka blocku oddelene carkou.
     *
     * @var string
     */
    private $blockIds = '';

	/**
     * Popis stranky.
     *
     * @var string
     */
    private $description = '';

    /**
     * Titulek stranky.
     *
     * @var string
     */
	private $title = '';

	/**
     * Cesta k favicone.
     *
     * @var string
     */
    private $favicon = '';

	/**
     * Objekt MetaTagList
     *
     * @var MetaTagList
     */
    private $metaTagList = NULL;

	/**
     * Objekt CssList
     *
     * @var CssList
     */
    private $cssList = NULL;

	/**
     * Objekt JavaScriptList
     *
     * @var JavaScriptList
     */
    private $javaScriptList = NULL;

	/**
     * Objekt FeedList.
     *
     * @var FeedList
     */
    private $feedList = NULL;

    /**
     * Objekt ContainerList.
     *
     * @var ContainerList
     */
	private $containerList = NULL;



	public function __construct($id)
	{
		$this->loadById($id);
	}

	public function loadById($id)
	{
        if(FALSE === $this->loadFromCache()) {
            $query = "SELECT p.`id`, p.`pageid_node_id`, p.`block_ids`, p.`description`, pn.`css`, pn.`template`
                FROM `" . Config::DB_PREFIX . "pageid` p
                JOIN `" . Config::DB_PREFIX . "pageid_node` pn ON p.`pageid_node_id` = pn.`id`
                WHERE p.`id` = " . $id
                . "LIMIT 1";
            $result = dibi::query($query)->fetch();
            $this->importRecord($result);
            $this->saveToCache();
        }
	}

	private function importRecord($record)
	{
		$this->id = $record['id'];
		$this->pageNodeId = $record['pageid_node_id'];
		$this->blockIds = $record['block_ids'];
		$this->description = $record['description'];
		$this->cssList = new CssList($record['css']);
		$this->template = $record['template'];
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
	 * @return void
	 */
	protected function setId($id)
	{
		if (! is_numeric($id)) {
			throw new InvalidArgumentException('Promena $id musi byt datoveho typu integer.');
		}
		$id = (int)$id;
		$this->id = $id;
	}

	/**
	 * Vrati hodnotu vlastnosti $template
	 *
	 * @param void
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
	 * @return void
	 */
	protected function setTemplate($template)
	{
		if (! is_string($template) && ! is_numeric($template)) {
			throw new InvalidArgumentException('Promena $template musi byt datoveho typu string.');
		}
		$template = (string)$template;
		$this->template = $template;
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
	 * @return void
	 */
	protected function setTitle($title)
	{
		if (! is_string($title) && ! is_numeric($title)) {
			throw new InvalidArgumentException('Promena $title musi byt datoveho typu string.');
		}
		$title = (string)$title;
		$this->title = $title;
	}

	/**
	 * Vrati hodnotu vlastnosti $favicon
	 *
	 * @param void
	 * @return string
	 */
	public function getFavicon()
	{
		return $this->favicon;
	}

	/**
	 * Nastavi hodnotu vlastnosti $favicon
	 *
	 * @param string
	 * @return void
	 */
	protected function setFavicon($favicon)
	{
		if (! is_string($favicon) && ! is_numeric($favicon)) {
			throw new InvalidArgumentException('Promena $favicon musi byt datoveho typu string.');
		}
		$favicon = (string)$favicon;
		$this->favicon = $favicon;
	}

	/**
	 * Vrati hodnotu vlastnosti $metaTagList
	 *
	 * @param void
	 * @return array
	 */
	public function getMetaTagList()
	{
		return $this->metaTagList;
	}

	/**
	 * Nastavi hodnotu vlastnosti $metaTagList
	 *
	 * @param array
	 * @return void
	 */
	protected function setMetaTagList($metaTagList)
	{
		if (! is_array($metaTagList)) {
			throw new InvalidArgumentException('Promena $metaTagList musi byt datoveho typu array.');
		}
		$metaTagList = (array)$metaTagList;
		$this->metaTagList = $metaTagList;
	}

	/**
	 * Vrati hodnotu vlastnosti $cssList
	 *
	 * @param void
	 * @return Css
	 */
	public function getCssList()
	{
		return $this->cssList;
	}

	/**
	 * Nastavi hodnotu vlastnosti $cssList
	 *
	 * @param array
	 * @return void
	 */
	protected function setCssList($cssList)
	{
		if (! is_array($cssList)) {
			throw new InvalidArgumentException('Promena $cssList musi byt datoveho typu array.');
		}
		$cssList = (array)$cssList;
		$this->cssList = $cssList;
	}

	/**
	 * Vrati hodnotu vlastnosti $javaScriptList
	 *
	 * @param void
	 * @return array
	 */
	public function getJavaScriptList()
	{
		return $this->javaScriptList;
	}

	/**
	 * Nastavi hodnotu vlastnosti $javaScriptList
	 *
	 * @param array
	 * @return void
	 */
	protected function setJavaScriptList($javaScriptList)
	{
		if (! is_array($javaScriptList)) {
			throw new InvalidArgumentException('Promena $javaScriptList musi byt datoveho typu array.');
		}
		$javaScriptList = (array)$javaScriptList;
		$this->javaScriptList = $javaScriptList;
	}

	/**
	 * Vrati hodnotu vlastnosti $feedList
	 *
	 * @param void
	 * @return array
	 */
	public function getFeedList()
	{
		return $this->feedList;
	}

	/**
	 * Nastavi hodnotu vlastnosti $feedList
	 *
	 * @param array
	 * @return void
	 */
	protected function setFeedList($feedList)
	{
		if (! is_array($feedList)) {
			throw new InvalidArgumentException('Promena $feedList musi byt datoveho typu array.');
		}
		$feedList = (array)$feedList;
		$this->feedList = $feedList;
	}

	/**
	 * Vrati hodnotu vlastnosti $containerList
	 * pokud neni nastavena nastavi ji
	 *
	 * @param void
	 * @return array
	 */
	public function getContainerList()
	{
		if (empty($this->containerList)) {
			$this->setContainerList();
		}
		return $this->containerList;
	}

	/**
	 * Loadne informace o containerech spolecne s blokama
	 *
	 * @param array
	 * @return void
	 */
	protected function setContainerList()
	{
		if(empty($this->blockIds)){
			throw new InvalidArgumentException('Nemuzu nastavit kontejnery proze neznam bloky.');
		}
		$this->containerList = new ContainerList($this->blockIds);
	}

	/**
	 * Vrati hodnotu vlastnosti $pageNodeId
	 *
	 * @param void
	 * @return integer
	 */
	public function getPageNodeId()
	{
		return $this->pageNodeId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $pageNodeId
	 *
	 * @param integer
	 * @return void
	 */
	protected function setPageNodeId($pageNodeId)
	{
		if (! is_numeric($pageNodeId)) {
			throw new InvalidArgumentException('Promena $pageNodeId musi byt datoveho typu integer.');
		}
		$pageNodeId = (int)$pageNodeId;
		$this->pageNodeId = $pageNodeId;
	}

	/**
	 * Vrati hodnotu vlastnosti $blockIds
	 *
	 * @param void
	 * @return string
	 */
	public function getBlockIds()
	{
		return $this->blockIds;
	}

	/**
	 * Nastavi hodnotu vlastnosti $blockIds
	 *
	 * @param string
	 * @return void
	 */
	protected function setBlockIds($blockIds)
	{
		if (! is_string($blockIds) && ! is_numeric($blockIds)) {
			throw new InvalidArgumentException('Promena $blockIds musi byt datoveho typu string.');
		}
		$blockIds = (string)$blockIds;
		$this->blockIds = $blockIds;
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
	 * @return void
	 */
	protected function setDescription($description)
	{
		if (! is_string($description) && ! is_numeric($description)) {
			throw new InvalidArgumentException('Promena $description musi byt datoveho typu string.');
		}
		$description = (string)$description;
		$this->description = $description;
	}

    public function getCacheId()
    {
        return '/kernel/page/' . $this->id. '/' . $this->getClass();
    }
}