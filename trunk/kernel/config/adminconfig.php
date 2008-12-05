<?php

class AdminConfig implements ArrayAccess
{

	private $settings = array(
		'CACHEMODE'	=>	TRUE,
		'EMIL'		=>	'IndexEmil'
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
			$defaultConfig = new DefaultConfig;
			return $defaultConfig[$name];
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