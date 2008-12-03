<?php

class HttpGet extends HttpProperty
{
	private static $isAssigned = FALSE;

	private $get = array();

	private $propertyName = 'get';

	public function assign(array $GET)
	{
		if (FALSE === self::$isAssigned) {
			$this->get = $GET;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_GET jiz byl nacten.');
		}
	}
}