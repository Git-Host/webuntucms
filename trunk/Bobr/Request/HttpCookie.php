<?php

class Bobr_Request_HttpCookie extends Bobr_Request_HttpProperty
{
	private static $isAssigned = FALSE;

	private $cookie = array();

	private $propertyName = 'cookie';

	public function assign(array $COOKIE)
	{
		if (FALSE === self::$isAssigned) {
			$this->cookie = $COOKIE;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_COOKIE jiz byl nacten.');
		}
	}
}