<?php

class HttpPost extends HttpProperty
{
	private static $isAssigned = FALSE;

	private $post = array();

	private $propertyName = 'post';

	public function assign(array $POST)
	{
		if (FALSE === self::$isAssigned) {
			$this->post = $POST;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_POST jiz byl nacten.');
		}
	}
}