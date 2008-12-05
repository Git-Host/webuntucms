<?php

abstract class HttpProperty implements IHttpProperty
{

	public function __get($name)
	{
		if (isset($this->{$this->propertyName}[$name])) {
			return $this->{$this->propertyName}[$name];
		} else {
			throw new InvalidArgumentException("Promena $name neexistuje." . $this->propertyName);
		}
	}

	public function __isset($name)
	{
		return isset($this->{$this->propertyName}[$name]);
	}

}
?>