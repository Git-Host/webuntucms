<?php
/**
 * Je to kolekce objektu Container.
 *
 * @author rbas
 */
class Bobr_Page_ContainerColection extends Bobr_Colection
{
    /**
     * Unikatni identifikator stranky.
     *
     * @var integer
     */
    private $pageId = 0;

    public function  __construct()
    {
        $this->colectionClass = 'Bobr_Page_Container';
    }

    /**
     * Vytvori kolekci Containeru a zavola imporotovani blocku do ni.
     *
     * @param array $record
     * @throws Bobr_Page_ContainerColectionException Pokud je znemozneno sestavit kolekci.
     * @return Bobr_Page_ContainerColection
     */
    public function assign(array $record)
    {
        if (!$this->loadFromCache()) {
            // Vytvorime si idcka kontejneru.
            $containerIds = implode(',', array_keys($record));
            // Nactem informace o kontejnerech.
            $this->loadContainer($containerIds);

            try {
                // Projdem kontejnery.
                foreach ($this as $container) {
                    // Vytvorime si seznam blocku v kontejneru.
                    $blockIds = implode(',', array_keys($record[$container->id]));
                    // Nactem a blocky.
                    $container->loadBlockByIds($blockIds);
                }
            } catch (Bobr_Page_ContainerException $e) {
                // Tato vyjimka znamena ze se nemuzou sestavit kontejnery.
                throw new Bobr_Page_ContainerColectionException($e->getMessage());
            }
            
            $this->saveToCache();
        }
        return $this;
    }

    /**
     * Nacte kontejery z databaze a zavola funkci importContainer.
     *
     * @param string $containerIds Cisla kontejneru oddelene carkou
     * @throws Bobr_Page_ContainerColectionExceptin Pokud je znemozneno naimportovani dat.
     * @return Bobr_Page_ContainerColection
     */
    private function loadContainer($containerIds)
    {
        $query = 'SELECT `id`, `name`, `description`
            FROM `' . Config::DB_PREFIX . 'container`
            WHERE `id` IN (' . $containerIds . ')
            ORDER BY `id`';
        $record = dibi::query($query)->fetchAssoc('name');

        // Pokud nejsou data nemuzem sestavit kontejnery.
        if (empty($record)) {
            throw new Bobr_Page_ContainerColectionException('Zadny ze zadanych ('. $containerIds . ') kontejneru neexistuje.');
        }

        try {
            $this->importColection($record);
        } catch (Bobr_Page_ColectionException $e) {
            // Pri importu nastala chyba, nemuzem sestavit kontejnery.
            throw new Bobr_Page_ContainerColectionException($e->getMessage());
        }
        
        return $this;
    }

    /**
     * Vrati unikatni kesovy identifikator.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/page/' . $this->pageId . '/' . $this->getClass();
    }

    /**
     * Vrati hodnotu vlastnosti pageId.
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Nastavi hodnotu vlastnosti pageId.
     *
     * @param integer $pageId
     * @return Bobr_Page_ContainterColection
     */
    public function setPageId($pageId)
    {
        $this->pageId = (integer)$pageId;
        return $this;
    }
}