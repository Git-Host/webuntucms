<?php

interface IHttpProperty
{
	function assign(array $value);

	function __get($name);
}