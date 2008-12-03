<?php

class CssList extends Object
{
	private $items = array();

	public function __construct($cssLink)
	{
		$this->addCss($cssLink);
	}

	public function addCss($cssLink)
	{
		$this->items[] = new Css($cssLink);
	}
}