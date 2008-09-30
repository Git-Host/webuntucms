<?php
class LoginAdmin extends AbstractModule
{
		
	public function __construct( $block )
	{
		parent::__construct( $block );
	}
	
	protected function init()
	{
		$this->webInstance = 'admin';
	}
	
}
?>