<?php
class Bobr_User_Data extends Bobr_ObjectBox
{
	private $userId = 0;
	
	public function __construct($userId)
	{
		$this->setUserId($userId)
		->addObject(new Bobr_User($this->userId))
		->addObject(new Bobr_User_Access($this->userId));
		
	}
	
	/**
	 * Nastavi hodnotu vlastnosti $userId
	 * 
	 * @param integer $userId
	 * @return Bobr_User_Data
	 */
	public function setUserId($userId)
	{
		$this->userId = (integer) $userId;
		return $this;
	}
	/**
	 * Vrati objekt.
	 * 
	 * @return Bobr_User_Access
	 */
	public function getBobrUserAccess()
	{
		return $this->bobrUserAccess;
	}
	
	/**
	 * Kkk
	 * 
	 * @return Bobr_User
	 */
	public function getBobrUser()
	{
		return $this->bobrUser;
	}
	
	public function setBobrUser($value)
	{
		return $this->bobrUser = $value;
	}
}
?>