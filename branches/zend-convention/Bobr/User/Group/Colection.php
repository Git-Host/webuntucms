<?php
class Bobr_User_Group_Colection extends Bobr_Colection
{
	/**
	 * Id uzivatele ke kteremu se stahuji skupiny.
	 *
	 * @var integer
	 */
	private $userId;

	public function __construct($userId = 0)
	{
		$this->setColectionClass('Bobr_User_Group');
		if (0 != $userId) {
			$this->setUserId($userId);
			$this->load();
		}
	}
	/**
	 * Nacte data.
	 * 
	 * @return Bobr_User_Group_Colection
	 */
	public function load()
	{
		if (0 === $this->userId) {
			throw new Bobr_User_GroupColectionIAException('Neni vyplneno id uzivatele pro ktereho mam nacist skupiny.');
		}

		if (!$this->loadFromCache()) {
			$query = 'SELECT g.`id`, g.`pid`, g.`title`, g.`description`
				FROM `' . Config::DB_PREFIX . 'groups` g
				JOIN `' . Config::DB_PREFIX . 'user_groups` ug ON g.`id` = ug.`group_id`
				WHERE ug.`user_id` = ' . $this->userId;

			$record = dibi::query($query)->fetchAssoc('id');
			if (empty($record)) {
				return NULL;
			}
			$this->importColection($record);
			$this->saveToCache();
		}
		return $this;
	}

	public function getCacheId()
	{
		return '/bobr/user/' . $this->getUserId() . '/' . $this->getClass();
	}
	
	/**
	 * Vrati pole pro import nebo export dat v zavislosti na $type.
	 * 
	 * @param string $type
	 * @return array
	 */
	protected function getRecordMap($type)
	{
		static $map = array(
			'user_id' => 'userId',
		);
		return $this->returnMap($type, $map);
	}

	/**
	 * Vrati hodnotu vlastnosti id.
	 *
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	/**
	 * Nastavi hodnotu vlastnosti id.
	 *
	 * @param integer $userId
	 * @return Bobr_User_GroupColection
	 */
	public function setUserId($userId)
	{
		$this->userId = (integer)$userId;
		return $this;
	}

	/**
	 * Vrati seznam group v asociativnim poly.
	 *
	 * @return array
	 */
	public function getGroupList()
	{
		if (empty($this->colection)) {
			throw new Bobr_User_GroupColectionIAException('Nejsou nactene skupiny.');
		}
		foreach ($this->colection as $key => $group) {
			$array[$key] = $group->name;
		}
		return $array;
	}

	public function getGroupIdToSql()
	{
		return implode(',' , array_keys($this->colection));
	}
}