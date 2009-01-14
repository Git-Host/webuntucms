<?php

class Kernel_Request_HttpHeader
{
	private $headers = array();

	private static $isAssigned = FALSE;

	public function assign($headers = NULL)
	{
		// Ziskame hlavicky serveru
		if (function_exists('apache_request_headers')) {
			$this->headers = array_change_key_case(apache_request_headers(), CASE_LOWER);
		} else {
			foreach ($_SERVER as $key => $value) {
				if (strncmp($key, 'HTTP_', 5) == 0) {
					$this->headers[ strtr(strtolower(substr($key, 5)), '_', '-') ] = $value;
				}
			}
		}
	}

	public function __get($name)
	{
		if (isset($this->headers[$name])) {
			return $this->headers[$name];
		} else {
			throw new InvalidArgumentException("$name neexistuje.");
		}
	}

	public function __isset($name)
	{
		return isset($this->headers[$name]);
	}
}