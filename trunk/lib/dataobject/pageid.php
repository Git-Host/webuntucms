<?php
class PageId extends Object
{
	private static $instance = FALSE;

	private $pageIdList;
	private $cache;

	// database field list
	protected	$id;
	protected	$pageid_node_id;
	protected	$webinstance_id;
	protected	$block_ids;
	protected	$description;
	// pageIdNode
	protected	$css;
	protected	$template;
	protected	$pageIdNodeList;

	const CACHEID_LIST = 'all_pageid_data';

	public static function getInstance()
	{
		if(self::$instance === FALSE) {
			self::$instance = new PageId();
		}
		return self::$instance;
	}

	private function __construct()
	{
		$this->cache = new Cache('data/kernel/');
	}

	public function getPageIdList()
	{
		if($this->pageIdList) {
			return $this->pageIdList;
		} else {
			return $this->setPageIdList();
		}
	}

	private function setPageIdList()
	{
		$sql =
			"SELECT p.id, p.block_ids, p.webinstance_id, pn.css, pn.template
			FROM ".BobrConf::DB_PREFIX."pageid p
			JOIN ".BobrConf::DB_PREFIX."pageid_node pn ON p.pageid_node_id = pn.id";
		$this->pageIdList = $this->cache->loadData(self::CACHEID_LIST, $sql, 'id');
		return $this->pageIdList;
	}

	private function invalidPageIdList()
	{
		unset($this->pageIdList);
		return $this->cache->invalid(self::CACHEID_LIST);
	}

	private function insertPageId()
	{
		$result = dibi::query(
			"INSERT INTO ".BobrConf::DB_PREFIX."pageid
			(pageid_node_id, webinstance_id, block_ids, description)
			VALUES(".pg_escape_string($this->pageid_node_id).", "
			.pg_escape_string($this->webinstance_id).", '"
			.pg_escape_string($this->block_ids).", '"
			.pg_escape_string($this->description)."')");
		if(TRUE === $result) {
			$this->invalidPageIdList();
			return $result;
		} else {
			return $result;
		}
	}

	private function updatePageId()
	{
		if(NULL == $this->id) {
			Message::addError('Nebylo zadano ID ktere se ma updatnout.');
			return FALSE;
		} else {
			$result = dibi::query(
				"UPDATE ".BobrConf::DB_PREFIX."pageid SET
				pageid_node_id = ".pg_escape_string($this->pageid_node_id).",
				webinstance_id = ".pg_escape_string($this->webinstance_id).",
				block_ids = '".pg_escape_string($this->block_ids)."',
				description = '".pg_escape_string($this->description)."'
				WHERE id = ".pg_escape_string($this->id)
			);
			if(TRUE === $result) {
				$this->invalidPageIdList();
				return $result;
			} else {
				return $result;
			}
		}
	}

	protected function getPageIdNodeList($id = NULL)
	{
		if(NULL === $id) {
			$andWhere = '';
		} else {
			$andWhere = ' WHERE id = '.$id;
		}
		$result = dibi::query("SELECT id, css, template FROM ".BobrConf::DB_PREFIX."pageid_node ".$andWhere." ORDER BY id");
		return $result->fetchAssoc('id');
	}

	private function insertPageIdNodeList()
	{
		$result = dibi::query("INSERT INTO ".BobrConf::DB_PREFIX."pageid_node(css, template)
			VALUES(".pg_escape_string($this->css).", ".pg_escape_string($this->template)."')"
		);
		if(TRUE === $result) {
			$this->invalidPageIdList();
		}
		return $result;
	}

	private function updatePageIdNodeList()
	{
		if(NULL == $this->id) {
			Message::addError('Nebylo zadano ID ktere se ma updatnout.');
			return FALSE;
		} else {
			$result = dibi::query("UPDATE ".BobrConf::DB_PREFIX."pageid_node SET
				css = ".pg_escape_string($this->css).",
				template = ".pg_escape_string($this->template).",
				WHERE id = ".pg_escape_string($this->id));
			if(TRUE === $result) {
				$this->invalidPageIdList();
				return $result;
			} else {
				return $result;
			}
		}
	}
}