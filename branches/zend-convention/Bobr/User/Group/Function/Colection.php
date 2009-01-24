<?php
class Bobr_User_Group_Function_Colection extends Bobr_Colection
{
	/**
	 * Id skupiny pro kerou se budou nacitat prava.
	 *
	 * @var integer
	 */
	private $groupId = 0;

	public function __construct($groupId = 0)
	{
		$this->setColectionClass('Bobr_User_Group_Function');

		if ($groupId > 0) {
			$this->setGroupId($groupId);
			$this->load();
		}
	}

	public function load()
	{
		if ($this->groupId < 1) {
			throw new Bobr_User_Group_FunctionColectionIAException('Neni vyplneno id skupiny pro kterou mam nacist prava.');
		}

		if (!$this->loadFromCache()) {
			$query = 'SELECT mf.`id`, mf.`module_id`, mf.`func`, mf.`description_id`, mf.`author`, mf.`funcversion`, mf.`bobrversion`
				FROM `' . Config::DB_PREFIX . 'module_functions` mf
				JOIN `' . Config::DB_PREFIX . 'group_functions` gf ON mf.`id` = gf.`module_function_id`
				WHERE gf.`group_id` = ' . $this->groupId . '
				ORDER BY mf.`module_id`, mf.`id`';
			$record = dibi::query($query)->fetchAssoc('id');
			if (empty($record)) {
				return NULL;
			}

			$this->importColection($record)
				->saveToCache();
		}
	}

	/**
	 * Vrati kesove id
	 *
	 * @return string
	 */
	public function getCacheId()
	{
		return '/bobr/group/' . $this->getGroupId() . '/' . $this->getClass();
	}

	/**
	 * Vrati hodnotu vlastnosti groupId
	 *
	 * @return integer
	 */
	public function getGroupId()
	{
		return $this->groupId;
	}

	/**
	 * Nastavi hodnotu vlastnosti groupId
	 *
	 * @param integer $groupId
	 * @return Bobr_User_Group_FunctionColection
	 */
	public function setGroupId($groupId)
	{
		$this->groupId = (integer)$groupId;
		return $this;
	}
}