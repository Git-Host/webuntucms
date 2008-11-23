<?php

class HttpFile extends HttpProperty
{
	private static $isAssigned = FALSE;

	private $file = array();

	private $propertyName = 'file';

	public function assign(array $FILE)
	{
		if (FALSE === self::$isAssigned) {
			$this->file = $FILE;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_FILE jiz byl nacten.');
		}
	}
}