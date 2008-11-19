<?php

class WebInstance extends Object
{
	private $id = 0;
	// 24 znaku
	private $title	= '';
	// 512 znaku
	private $description = '';

	public function importRecord(array $record)
	{
		$this->id			=	$record['id'];
		$this->title		=	$record['title'];
		$this->description	=	$record['description'];

		return $this;
	}
}