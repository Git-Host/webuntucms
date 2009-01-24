<?php
class Bobr_User_Group_Simple extends Bobr_Colection
{
	private $groupId;
	private $webInstanceIds;
	
	public function __construct($groupId, array $webInstanceIds)
	{
		$this->setColectionClass('Bobr_Module_Function_Simple');
		$this->webInstanceIds = implode(',', $webInstanceIds);
		$this->load($this->webInstanceIds);
	}
	
	public function load($webInstanceIds)
	{
		$query = 'SELECT f.id, f.func 
			FROM bobr_group_functions_webinstance w
			JOIN bobr_module_functions f ON w.module_function_id = f.id 
			WHERE w.group_id = 1
			AND w.webinstance_id IN (' . $webInstanceIds . ')';
		$record = dibi::query($query)->fetchAssoc('id');
		$this->importColection($record);
	}
	protected function getRecordMap($type)
	{
		static $map = array (
			'group_id' => 'groupId'
		);
		return $this->returnMap($type, $map);
	}
	public function setGroupId($groupId)
	{
		$this->groupId = (integer) $groupId;
		return $this;
	}
	public function getCacheId(){}
}