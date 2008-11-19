<?php

class DefaultConfig implements ArrayAccess
{

	private $settings = array(
		'KUBULA'	=>	'DefaultConfig',
		'EMANUEL'	=>	'Default Emanuel'
	);

	public function offsetExists( $name )
	{
		$value = strtoupper( $name );
		return isset($this->settings[$name]);
	}

	public function offsetGet( $name )
	{
		$value = strtoupper( $name );
		if(isset($this->settings[$value])){
			return $this->settings[ $value ];
		}else{
			return 'neexistuje';
		}
	}

	public function offsetSet( $name, $value )
	{
		$name = strtoupper( $name );
		$this->settings[$name] = $value;
	}

	public function offsetUnset( $name )
	{
		unset($this->settings[$name]);
	}

	public function __get($name)
	{
		return $this->offsetGet($name);
	}
}