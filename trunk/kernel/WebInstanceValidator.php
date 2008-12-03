<?php

class WebInstanceValidator
{
	private $webInstanceList = array();

	private $matchPattern = '';

	public function __construct()
	{
		$user = Session::getInstance()->user;
		$this->webInstanceList = $user->webInstance;
	}

	public function validate($webInstance)
	{
		if (empty($this->matchPattern)) {
			$this->setMatchPattern();
		}
		if (0 === preg_match($this->matchPattern, $webInstance)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	private function setMatchPattern()
	{
		return $this->matchPattern = '@' . implode('|', $this->webInstanceList) . '@';
	}
}