<?php
final class ProcessIndex extends ProcessWeb
{
	
	protected function setRoot(){
		$this->root = $this->config['WEB_ROOT'];
	}
	
}