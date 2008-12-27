<?php

final class UserValidator extends Object
{
	public function __construct()
	{
	}

	/**
     * Zvaliduje jestli v session je paltny objekt User.
     *
     * @return boolean
     */
    public function validate()
	{
		$session = Session::getInstance();
		if(isset($session->user) && $session->user instanceof User){
			return TRUE;
		}else{
			return FALSE;
		}
	}

    /**
     * Vytvori ze vstupni hodnoty hash.
     *
     * @param string $password
     * @return string
     */
    public static function generatePassword($password)
    {
        return sha1($password);
    }
}