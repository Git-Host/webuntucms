<?php
/**
 * Abstraktni trida pro konfiguracni soubory.
 * Metoda offsetGet se muze lisit od druhu konfigu.
 *
 * @author rbas
 */
abstract class AbstractConfig implements ArrayAccess
{
    /**
     * Pole konfiguracnich promenych
     *
     * @var array
     */
    protected $settings = array();

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

    public function  __set($name, $value)
    {
        $this->offsetSet($name, $value);
    }

    public function  __unset($name)
    {
        $this->offsetUnset($name);
    }

    public function  __isset($name)
    {
        return $this->offsetExists($name);
    }
}