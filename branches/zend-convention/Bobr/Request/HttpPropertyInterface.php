<?php

interface Bobr_Request_HttpPropertyInterface
{
	function assign(array $value);

	function __get($name);
}