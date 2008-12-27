<?php
/**
 * Slouzi k propojeni vazem mezi PageId, Container a Block.
 * Obsahuje v sobe ContainerColection.
 *
 * @author rbas
 */
class Page extends DataObject
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
     * @var ContainerColection
     */
    private $containerColection = NULL;

    /**
     * Objekt PageId.
     *
     * @var PageId
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
     * @return Page
     * @throws PageIAException Pokud neni vyplnena vlastnosti id.
     * @throws PageException Pokud nenajde zadne data.
     */
    public function load()
    {
        if (0 > $this->id) {
            throw new PageIAException('Neni zadano pageId ktere se ma nacist.');
        }

        if (!$this->loadFromCache()) {
            $query = 'SELECT `container_id`, `block_id`, `weight`
                FROM `' . Config::DB_PREFIX . 'pageid_container_block`
                WHERE `pageid_id` = ' . $this->id
                . ' ORDER BY `container_id`, `weight`';
            $data = dibi::query($query)->fetchAssoc('container_id,block_id');

            if (empty($data)) {
                throw new PageException('Zadana stranka nema zadne data.');
            }

            // Naimportujem data.
            $this->importRecord($data);

            try {
                $this->setPageId(new PageId($this->id));
            } catch (PageIdException $e) {
                // Nelze nacist PageId nelze sestavit Page.
                throw new PageException($e->getMessage());
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
     * @throws PageException Pokud se nepovede sestavit kontejnery.
     * @return Page
     */
    public function importRecord(array $record)
    {
        try {
            $this->setContainerColection(new ContainerColection);
            $this->containerColection->setPageId($this->id);
            $this->containerColection->assign($record);
        } catch (ContainerColectionException $e) {
            throw new PageException($e->getMessage());
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
     * @return Page
     */
    public function setId($id)
    {
        $this->id = (integer)$id;
        return $this;
    }

    /**
     * Nastavi hodnotu vlastnosti containerColection.
     *
     * @return ContainerColection
     */
    public function getContainerColection()
    {
        return $this->containerColection;
    }

    /**
     * Nastavi hodnotu valstnosti containerColection.
     *
     * @param ContainerColection $containerColection
     * @return Page
     */
    public function setContainerColection(ContainerColection $containerColection)
    {
        $this->containerColection = $containerColection;
        return $this;
    }

    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Nastavi vlastnost pageId.
     *
     * @param PageId $pageId
     * @return Page
     */
    public function setPageId(PageId $pageId)
    {
        $this->pageId = $pageId;
        return $this;
    }
}