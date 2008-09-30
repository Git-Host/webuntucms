<?php
/**
 * Rozhrani pro pretezovani pole
 * @return 
 * @param $name Object
 */
class Settings implements ArrayAccess
{
	public function offsetExists( $name )
	{
		return $this->existsVariable( $name  );
	}
	
	public function offsetGet( $name )
	{
		return $this->getVariable( $name );
	}
	
	public function offsetSet( $name, $value )
	{
		return $this->setVariable( $name, $value );
	}
	
	public function offsetUnset( $name )
	{
		return $this->unsetVariable( $name );
	}
	
	/**
	 * Zjisti jestli dana promena existuje
	 * @return 
	 * @param $name Object
	 */
	public function existsVariable( $name )
	{
	}
	
	/**
	 * Vrati hodnoty promene
	 * @return 
	 * @param $name Object
	 */
	public function getVariable ( $name )
	{
	}
	
	/**
	 * Nastavi promeneou 
	 * @return bool
	 * @param $name Object
	 * @param $value Object
	 */
	public function setVariable ( $name, $value )
	{
	}
	
	/**
	 * Odnastavi promeneou
	 * @return 
	 * @param $name Object
	 */
	public function unsetVariable( $name )
	{
	}
}
