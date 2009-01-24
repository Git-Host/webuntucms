<?php
class Bobr_User_Access extends Bobr_Colection
{
	private $userId = 0;
	
	public function __construct($userId)
	{
		$this->userId = $userId;
		$this->setColectionClass('Bobr_User_Group_Simple');
	}
	/**
	 * Nacte data.
	 * 
	 * @return Bobr_User_Access
	 */
	public function load()
	{
		if (!$this->loadFromCache()) {
			$query = 'SELECT ug.group_id, gw.webinstance_id 
				FROM bobr_user_groups ug
				JOIN bobr_group_webinstance gw ON ug.group_id = gw.group_id
				WHERE ug.user_id = ' . $this->userId;
			$record = dibi::query($query)->fetchAll();
			if (empty($record)) {
				return NULL;
			} else {
				$this->importColection($this->transformData($record))
				->saveToCache();				
			}
		}
		return $this;
	}
	/**
	 * Predela data.
	 * 
	 * @param array $record
	 * @return ArrayObject
	 */
	public function transformData($record)
	{
		foreach ($record as $row) {
			$groups[$row['group_id']][] = $row['webinstance_id'];
		}
		$groups = new ArrayObject($groups, 2);
		return $groups;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see zend-convention/Bobr/Bobr_Colection#importColection()
	 */
	public function importColection(ArrayObject $record)
	{
		foreach ($record as $id => $data) {
			$this->colection[$id] = new $this->colectionClass($id, $data);
		}
		return $this;
	}
	/**
	 * (non-PHPdoc)
	 * @see zend-convention/Bobr/Bobr_DataObjectInterface#getCacheId()
	 */
	public function getCacheId()
	{
		return '/bobr/user/' . $this->userId . '/' . $this->getClass();
	}
	/**
	 * Vrati hotnotu vlastnosti $userId.
	 * 
	 * @return integer
	 */
	public function getUserId()
	{
		return $this->userId;
	}
	/**
	 * Nastavi hodnotu vlastnosti $userId.
	 * 
	 * @param integer $userId
	 * @return Bobr_User_Access
	 */
	public function setUserId($userId)
	{
		$this->userId = (integer) $userId;
		return $this; 
	}
	protected function getRecordMap($type){}
}