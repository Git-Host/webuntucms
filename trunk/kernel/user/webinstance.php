<?php

class WebInstance extends Object
{
	private $id = 0;
	// 24 znaku
	private $name	= '';
	// 512 znaku
	private $description = '';

	public function importRecord(array $record)
	{
		$this->id			=	$record['id'];
		$this->name			=	$record['title'];
		$this->description	=	$record['description'];

		return $this;
	}

	public function getName()
	{
		return $this->name;
	}
}