<?php
/**
 * Slouzi k propojeni vazem mezi PageId, Container a Block.
 * Obsahuje v sobe ContainerColection.
 *
 * @author rbas
 */
class Kernel_Page_Page extends Kernel_DataObject
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
     * @var Kernel_Page_ContainerColection
     */
    private $containerColection = NULL;

    /**
     * Objekt PageId.
     *
     * @var Kernel_Page_PageId
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
     * @return Kernel_Page_Page
     * @throws Kernel_Page_PageIAException Pokud neni vyplnena vlastnosti id.
     * @throws Kernel_Page_PageException Pokud nenajde zadne data.
     */
    public function load()
    {
        if (0 > $this->id) {
            throw new Kernel_Page_PageIAException('Neni zadano pageId ktere se ma nacist.');
        }

        if (!$this->loadFromCache()) {
            $query = 'SELECT `container_id`, `block_id`, `weight`
                FROM `' . Kernel_Config_Config::DB_PREFIX . 'pageid_container_block`
                WHERE `pageid_id` = ' . $this->id
                . ' ORDER BY `container_id`, `weight`';
            $data = dibi::query($query)->fetchAssoc('container_id,block_id');

            if (empty($data)) {
                throw new Kernel_Page_PageException('Zadana stranka nema zadne data.');
            }

            // Naimportujem data.
            $this->importRecord($data);

            try {
                $this->setPageId(new Kernel_Page_PageId($this->id));
            } catch (Kernel_Page_PageIdException $e) {
                // Nelze nacist PageId nelze sestavit Page.
                throw new Kernel_Page_PageException($e->getMessage());
            }

            // Ulozime Page do cache.
            $this->saveToCache();
        }

        return $this;
    }

    /**
     * Vytvori objekt ContainerColection a da mu potrebne data pro sestaveni kontejneru.
     * 
     * @param array $record 
     * @throws Kernel_Page_PageException Pokud se nepovede sestavit kontejnery.
     * @return Kernel_Page_Page
     */
    public function importRecord(array $record)
    {
        try {
            $this->setContainerColection(new Kernel_Page_ContainerColection);
            $this->containerColection->setPageId($this->id);
            $this->containerColection->assign($record);
        } catch (Kernel_Page_ContainerColectionException $e) {
            throw new Kernel_Page_PageException($e->getMessage());
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
     * @return Kernel_Page_Page
     */
    public function setId($id)
    {
        $this->id = (integer)$id;
        return $this;
    }

    /**
     * Nastavi hodnotu vlastnosti containerColection.
     *
     * @return Kernel_Page_ContainerColection
     */
    public function getContainerColection()
    {
        return $this->containerColection;
    }

    /**
     * Nastavi hodnotu valstnosti containerColection.
     *
     * @param Kernel_Page_ContainerColection $containerColection
     * @return Kernel_Page_Page
     */
    public function setContainerColection(Kernel_Page_ContainerColection $containerColection)
    {
        $this->containerColection = $containerColection;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti pageId
     *
     * @return Kernel_Page_PageId
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Nastavi vlastnost pageId.
     *
     * @param Kernel_Page_PageId $pageId
     * @return Kernel_Page_Page
     */
    public function setPageId(Kernel_Page_PageId $pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }
}