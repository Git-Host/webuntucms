<?php
/**
 * Slouzi k propojeni vazem mezi PageId, Container a Block.
 * Obsahuje v sobe ContainerColection.
 *
 * @author rbas
 */
class Bobr_Page_Page extends Bobr_DataObject
{
    /**
     * Cislo page.
     *
     * @var integer
     */
    private $id = 0;

    /**
     * Kolekce objektu container.
     *
     * @var Bobr_Page_ContainerColection
     */
    private $containerColection = NULL;

    /**
     * Objekt PageId.
     *
     * @var Bobr_Page_PageId
     */
    private $pageId = NULL;


    public function __construct($id = 0)
    {
        if (0 != $id) {
            $this->setId($id);
            $this->load();
        }
    }

    /**
     * Nacte data.
     *
     * @return Bobr_Page_Page
     * @throws Bobr_Page_PageIAException Pokud neni vyplnena vlastnosti id.
     * @throws Bobr_Page_PageException Pokud nenajde zadne data.
     */
    public function load()
    {
        if (0 > $this->id) {
            throw new Bobr_Page_PageIAException('Neni zadano pageId ktere se ma nacist.');
        }

        if (!$this->loadFromCache()) {
            $query = 'SELECT `container_id`, `block_id`, `weight`
                FROM `' . Config::DB_PREFIX . 'pageid_container_block`
                WHERE `pageid_id` = ' . $this->id
                . ' ORDER BY `container_id`, `weight`';
            $data = dibi::query($query)->fetchAssoc('container_id,block_id');

            if (empty($data)) {
                throw new Bobr_Page_PageException('Zadana stranka nema zadne data.');
            }

            // Naimportujem data.
            $this->importRecord($data);

            try {
                $this->setPageId(new Bobr_Page_PageId($this->id));
            } catch (Bobr_Page_PageIdException $e) {
                // Nelze nacist PageId nelze sestavit Page.
                throw new Bobr_Page_PageException($e->getMessage());
            }

            // Ulozime Page do cache.
            $this->saveToCache();
        }

        return $this;
    }

    /**
     * Vytvori objekt ContainerColection a da mu potrebne data pro sestaveni kontejneru.
     * 
     * @param ArrayObject $record 
     * @throws Bobr_Page_PageException Pokud se nepovede sestavit kontejnery.
     * @return Bobr_Page_Page
     */
    public function importRecord(ArrayObject $record)
    {
        try {
            $this->setContainerColection(new Bobr_Page_ContainerColection);
            $this->containerColection->setPageId($this->id);
            $this->containerColection->assign($record);
        } catch (Bobr_Page_ContainerColectionException $e) {
            throw new Bobr_Page_PageException($e->getMessage());
        }
        return $this;
    }

    /**
     * Vrati cestu k souboru kde je sablona.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->pageId->getTemplate();
    }

    /**
     * Vrati cestu k soubory s kaskadama.
     *
     * @return string
     */
    public function getCss()
    {
        return $this->pageId->getCss();
    }

    /**
     * Vrati unikatni kesovy identifikator.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/page/' . $this->id . '/' . $this->getClass();
    }

    /**
     * Vrati hodnotu vlastnosti id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Nastavi hodnotu vlastnosti id.
     *
     * @param integer $id
     * @return Bobr_Page_Page
     */
    public function setId($id)
    {
        $this->id = (integer)$id;
        return $this;
    }

    /**
     * Nastavi hodnotu vlastnosti containerColection.
     *
     * @return Bobr_Page_ContainerColection
     */
    public function getContainerColection()
    {
        return $this->containerColection;
    }

    /**
     * Nastavi hodnotu valstnosti containerColection.
     *
     * @param Bobr_Page_ContainerColection $containerColection
     * @return Bobr_Page_Page
     */
    public function setContainerColection(Bobr_Page_ContainerColection $containerColection)
    {
        $this->containerColection = $containerColection;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti pageId
     *
     * @return Bobr_Page_PageId
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Nastavi vlastnost pageId.
     *
     * @param Bobr_Page_PageId $pageId
     * @return Bobr_Page_Page
     */
    public function setPageId(Bobr_Page_PageId $pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }
}