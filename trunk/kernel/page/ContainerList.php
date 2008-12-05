<?php
/**
 * Container list je DataObject. Stara se tedy o svoje kesovani.
 * Nacte blocky ktere se mu predavani do konstructoru, nacte svoje hlavickove udaje
 * a to vsechno naiportuje do sebe. Vytvori si takovy strom objktu presne tak jak
 * do sebe zapadaji.
 *
 * @author rbas
 */
class ContainerList extends DataObject
{
	/**
     * Obsahuje v sobe objekty Container indexovane podle jejich id.
     *
     * @var array
     */
    private $items = array();

    /**
     * Hash udelany pomoci MD5 z blockIds.
     *
     * @var string
     */
    private $cacheId = '';

	/**
     * Vytvori cacheId, a nacte data.
     *
     * @param string $blockIds 
     */
    public function __construct($blockIds)
	{
        $this->cacheId = md5($blockIds);
        $this->load($blockIds);
	}

    /**
     * Nacte data.
     * Pokud jsou data v cachi nacte je z ni.
     * Pokud data v cachi nejsou nacte je z databaze a ulozi je do ni.
     *
     * @param string $blockIds 
     */
    public function load($blockIds)
    {
        if (FALSE === $this->loadFromCache()) {
            $this->loadByBlockIds($blockIds);
            $this->saveToCache();
        }
    }

    /**
     * Nacte blocky z databaze podle jejich id.
     * A susti nacitani hlavickovych udaju o kontejnerech.
     *
     * @param string $blockIds
     */
	private function loadByBlockIds($blockIds)
	{
		$query = "SELECT `id`, `module_id`, `container_id`, `command`, `description_id`, `weight`
			FROM `" . Config::DB_PREFIX . "block`
			WHERE `id` IN (" . $blockIds . ")
			ORDER BY `container_id`, `weight`";

		$data = dibi::query($query)->fetchAssoc('container_id,id');
		$this->loadContainer($data);
	}

    /**
     * Nacte informace o kontejnerech a spusti import dat.
     *
     * @param array $record
     */
	private function loadContainer(array $record)
	{
		if (empty($record)) {
			throw new InvalidArgumentException('Nenacetly se informace o blocich, nemuzu nacist kontejnery.');
		}

		$containerIds = implode(',', array_keys($record));

		$query = "SELECT `id`, `title`, `description`
			FROM `" . Config::DB_PREFIX . "container`
			WHERE `id` IN (" . $containerIds . ")";

		$data = dibi::query($query)->fetchAssoc('id');
		$this->importRecord($data, $record);

		//$this->importBlocks($record);
	}

    /**
     * Naimportuje data do sebe.
     *
     * @param array $containerArray
     * @param array $blocksArray
     */
	public function importRecord(array $containerArray, array $blocksArray)
	{
		if (empty($containerArray)) {
			throw new InvalidArgumentException('Zrejme v databazi neni zadny zaznam o kontejnerech.');
		}

		foreach ($containerArray as $id => $container) {
			// Vytvorime Container.
            $this->items[$id] = new Container;
            // Naimportujem do nej data.
			$this->items[$id]->importRecord($container)->importBlocks($blocksArray[$id]);
		}
	}

    /**
     * Naimportuje do Containeru blocky.
     *
     * @param array $record
     */
	private function importBlocks(array $record)
	{
		foreach ($this->items as $id => $container) {
			// Spustime metodu importBlocks v containeru.
            $container->importBlocks($record[$id]);
		}
	}

    /**
     * Vrati hodnotu vlastnosti Items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Nastavi hodnotu vlastnosti Items
     *
     * @param array $items
     * @return ContainerList
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Vrati svoje cacheId obohacene o cestu.
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/page/' . $this->getClass() . '/' . $this->cacheId;
    }

    /**
     * Nastavi cacheId.
     * (pouziva se jen kvuli obnovovani se z cache)
     *
     * @param string $cacheId 
     */
    public function setCacheId($cacheId)
    {
        $this->cacheId = $cacheId;
    }
}