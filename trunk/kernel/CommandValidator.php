<?php

class Kernel_CommandValidator
{
	private $commandList = array();

	private $matchPattern = '';

    public function __construct()
	{
		$user = Kernel_Session::getInstance()->user;
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

    /**
     * Vrati konkretni command na zaklade functionId.
     * Pokud command nenajde uzivatel nema pravo na konkretni funkcnost a vraci NULL.
     *
     * @param integer $functionId
     * @return mixed
     */
    public function getCommand($functionId)
    {
        if (isset($this->commandList[$functionId])) {
            return $this->commandList[$functionId];
        } else {
            return NULL;
        }
    }
}