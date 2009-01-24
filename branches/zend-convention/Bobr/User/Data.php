<?php
class Bobr_User_Data extends Object
{
	private $user = NULL;

	private $groupColection = NULL;

	public function __construct($userId)
	{
		$this->setUser(new Bobr_User($userId));
        $this->setGroupColection(new Bobr_User_Group_Colection($userId));
        //$userGroupFunction = new Bobr_User_Group_Function_Colection(1);
	}


	/**
	 * Vrati hodnotu vlasnosti $user
	 *
	 * @return Bobr_User
	 */
	public function getUser()
	{
		return $this->user;
	}
	/**
	 * Nastavi hodnotu vlastnosti $user.
	 *
	 * @param Bobr_User $user
	 * @return Bobr_User_Data
	 */
	public function setUser(Bobr_User $user)
	{
		$this->user = $user;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $groupColection
	 *
	 * @return Bobr_User_Group_Colection
	 */
	public function getGroupColection()
	{
		return $this->groupColection;
	}
	/**
	 * Nastavi hodnotu vlastnosti $groupColection
	 *
	 * @param Bobr_User_Group_Colection $groupColection
	 * @return Bobr_User_Data
	 */
	public function setGroupColection(Bobr_User_Group_Colection $groupColection)
	{
		$this->groupColection = $groupColection;
		return $this;
	}
}
?>