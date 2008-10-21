<?php
final class ProcessAdmin extends ProcessWeb
{

	protected function setRoot(){
		$this->root = $this->config['ADMIN_ROOT'];
	}

}