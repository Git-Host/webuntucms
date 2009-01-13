<?php

class Kernel_WebInstanceValidator
{
	private $webInstanceList = array();

	private $matchPattern = '';

	public function __construct()
	{
		$user = Kernel_Session::getInstance()->user;
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

    /**
     * Zjisti jestli se jedna o konkretni spustenou webInstanci
     *
     * @param mixed $webInstance
     * @return boolean
     */
    public function isCurrent($webInstance)
    {
        if (is_integer($webInstance) && (Lib_Tools::getWebInstance() === $this->webInstanceList[$webInstance])) {
            return TRUE;
        } elseif (is_string($webInstance) && (Lib_Tools::getWebInstance() === $webInstance)) {
            return TRUE;
        }

        return FALSE;
    }

	private function setMatchPattern()
	{
		return $this->matchPattern = '@' . implode('|', $this->webInstanceList) . '@';
	}
}