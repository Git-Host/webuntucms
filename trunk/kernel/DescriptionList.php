<?php
/**
 * Description of DescriptionList
 *
 * @author rbas
 */
class Kernel_DescriptionList extends Kernel_DataObject
{
    private  $lang = '';

    private $pageId = 0;

    private static $selfInstance = NULL;

    private $items = array();

    private static $idList = array();

    /**
     * Vrati svoji vlastni instanci
     *
     * @return Kernel_DescriptionList
     */
    public static function getInstance($lang = '', $pageId = '')
    {
        if (self::$selfInstance === NULL) {
			self::$selfInstance = new self($lang, $pageId);
		}
		return self::$selfInstance;
    }

    private function  __construct($lang = '', $pageId = '')
    {
        $this->lang = $lang;
        $this->pageId = $pageId;
    }

    /**
     * Prida do pole dalsi id k nacteni
     *
     * @param integer $id
     */
    public static function addId($id)
    {
        self::$idList[$id] = (integer) $id;
    }

    /**
     * Nacte popisky.
     *
     * @return DescriptionList
     */
    public function load()
    {
        if (!empty(self::$idList)) {
            $this->loadByIds(self::$idList);
        }
        return $this;
    }

    public function loadByIds($ids)
    {
        if(! $this->loadFromCache()) {

            if (is_array($ids)) {
                $ids = implode(',', $ids);
            } else {
                $ids = (string) $ids;
            }
            
            if (empty($this->lang)) {
                throw new ErrorException('Neni zadan jazyk pro ktery se maji nacist popisky.');
            }

            $query = "SELECT `id`, `title`, `description`
                FROM `" . Config::DB_PREFIX . "description_" . $this->lang . "`
                WHERE `id` IN (" . $ids . ")";

            $record = dibi::query($query)->fetchAll();
            
            if (!empty($record)) {
                $this->importRecord($record);
            } else {
                throw new ErrorException("Description id $id is out of range.");
            }
       }
    }

    public function importRecord(array $record)
    {
        foreach ($record as $id => $description) {
            $this->items[$description['id']] = new Kernel_Description;
            $this->items[$description['id']]->importRecord($description);
        }
    }

    public function getDescription($id)
    {
        try {
            if (!isset($this->items[$id])) {
                $this->loadByIds($id);
            }
            return $this->items[$id];
        } catch (ErrorException $e) {
            // @todo tady se to musi nekam nahlasit pouzit k tomu messanger bo tak neco.
            return NULL;
        }
    }

    public function getCacheId()
    {
        return '/kernel/page/' . $this->pageId . '/' . $this->getClass() . '/' . $this->lang;
    }

    public function  __destruct() {
        $this->saveToCache();
    }

    public function getLang()
    {
        return $this->lang;
    }

    public function setLang($lang)
    {
        $this->lang = (string) $lang;
        return $this;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    public function setPageId($pageId)
    {
        $this->pageId = (integer) $pageId;
        return $this;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    public function getSelfInstance()
    {
        return self::$selfInstance;
    }

    public function setSelfInstance($selfInstance)
    {
        self::$selfInstance = $selfInstance;
        return $this;
    }

    public function getIdList()
    {
        return self::$idList;
    }

    public function setIdList(array $idList)
    {
        self::$idList = $idList;
        return $this;
    }

}