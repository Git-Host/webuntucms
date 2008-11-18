<?php
class Block extends Object
{
	protected
			// Objekt Cache
		 	$cache,
		 	$blockList;

	private static $instance = FALSE;

	public static function getInstance()
	{
		if(self::$instance === FALSE) {
			self::$instance = new Block();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}

	public function loadBlockById($blocksId)
	{
		if(NULL === $this->blockList) {
			$sql =
				"SELECT b.id , b.command, b.description_id, b.weight, c.title as container, m.module
				FROM ".BobrConf::DB_PREFIX."block b
				JOIN ".BobrConf::DB_PREFIX."container c ON b.container_id = c.id
				JOIN ".BobrConf::DB_PREFIX."module m ON b.module_id = m.id
				WHERE b.id IN (".$this->validBlockId($blocksId).")
				ORDER BY container, b.weight";
			$result = dibi::query($sql);
			$result = $result->fetchAssoc('container,id');
			$this->initDescription($result);
			$this->blockList = $result;
		}
		return $this->blockList;
	}

	private function validBlockId($blocksId)
	{
		if(strlen($blocksId) > 0) {
			return $blocksId;
		} else {
			throw new BlockException("Pozadovana stranka nema blocky.".$blocksId);
		}
	}

	/**
	 * Vezme si z pole ktere generuje metoda
	 * loadBlockByIds vsechny description_id a zavola si
	 * module Description, ktery se postara o zbytek.
	 *
	 * @param $block array - pole blocku
	 * @return void
	 */
	private function initDescription($blocks)
	{
		if(FALSE === empty($blocks)) {
			$descriptionsId = '';
			foreach($blocks as $value) {
				foreach($value as $block) {
					$descriptionsId.= $block['description_id'].',';
				}
			}
			$descriptionsId = substr($descriptionsId, 0, -1);
			Description::setDescriptionList($descriptionsId);
		}
	}

	public function getBlockList()
	{
		return $this->blockList;
	}
}