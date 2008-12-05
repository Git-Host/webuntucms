<?php

class HttpGet
{
	private static $isAssigned = FALSE;

	private $get = array();
    
	public function assign(array $GET)
	{
		if (FALSE === self::$isAssigned) {
			$this->get = $GET;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_GET jiz byl nacten.');
		}
	}

    public function __get($name)
	{
		if (isset($this->get[$name])) {
			return $this->get[$name];
		} else {
			throw new InvalidArgumentException("Promena $name neexistuje.");
		}
	}

	public function __isset($name)
	{
		return isset($this->get[$name]);
	}
}