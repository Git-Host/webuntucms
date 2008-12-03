<?php

class CommandValidator
{
	private $commandList = array();

	private $matchPattern = '';

	public function __construct()
	{
		$user = Session::getInstance()->user;
		if (empty($user->commands)) {
			throw new Exception('Zrejme neni nastaven uzivatel nebo nema zadna prava.');
		}

		$this->commandList = $user->commands;
	}

	public function validate($command)
	{
		if (empty($this->matchPattern)) {
			$this->setMatchPattern();
		}

		if (0 === preg_match($this->matchPattern, $command)) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	private function setMatchPattern()
	{
		return $this->matchPattern = '@' . implode('|', $this->commandList) . '@';
	}
}