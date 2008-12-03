<?php

class ContainerList extends Object
{
	private $items = array();

	public function __construct($blockIds)
	{
		$this->loadByBlockIds($blockIds);
	}

	public function loadByBlockIds($blockIds)
	{
		$query = "SELECT `id`, `module_id`, `container_id`, `command`, `description_id`, `weight`
			FROM `" . Config::DB_PREFIX . "block`
			WHERE `id` IN (" . $blockIds . ")
			ORDER BY `container_id`, `weight`";

		$data = dibi::query($query)->fetchAssoc('container_id,id');
		$this->loadContainer($data);
	}

	public function loadContainer(array $record)
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

	public function importRecord(array $containerArray, array $blocksArray)
	{
		if (empty($containerArray)) {
			throw new InvalidArgumentException('Zrejme v databazi neni zadny zaznam o kontejnerech.');
		}

		foreach ($containerArray as $id => $container) {
			$this->items[$id] = new Container;
			$this->items[$id]->importRecord($container)->importBlocks($blocksArray[$id]);
		}
	}

	private function importBlocks(array $record)
	{
		foreach ($this->items as $id => $container) {
			$container->importBlocks($record[$id]);
		}
	}
}