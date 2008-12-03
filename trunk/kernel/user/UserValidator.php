<?php

final class UserValidator extends Object
{
	public function __construct()
	{
	}

	public function validate()
	{
		$session = Session::getInstance();
		if(isset($session->user) && $session->user instanceof User){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}