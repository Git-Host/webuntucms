<?php

/**
 * Zvaliduje session,
 * pokud je neplatna odstrani ji a nastavi novou
 */
final class Bobr_SessionValidator extends Object
{

	/**
	 * Privatni klic
	 *
	 * @var string
	 */
	private $privateKey	= 'sifra1';

	/**
	 * Verejny klic
	 *
	 * @var string
	 */
	private $publicKey	= 'sifra2';

	/**
	 * "sul"
	 *
	 * @var string
	 */
	private	$salt	  	= 'trochaSoli';

	/**
	 * IP adresa
	 *
	 * @var string
	 */
	private static $remoteAddr 	= '';


	public function __construct()
	{
		self::$remoteAddr = $_SERVER['REMOTE_ADDR'];
		$this->validate();
	}

	/**
	 * Vypocte hash a zjisti shodnost se session
	 *
	 * @param void
	 * @return void
	 */
	private function validate()
	{
		$session = Bobr_Session::getNamespace('validity');
		if(FALSE === isset($session)
			&& $session  != sha1(sha1($this->salt).sha1(self::$remoteAddr.sha1($this->publicKey)))
		){
			$this->setSession();
		}
	}

	/**
	 * Odnastavi celou session a nastavi ji novy has
	 *
	 * @param mixed Bobr_Session
	 * @return void
	 */
	private function setSession()
	{
		$session = Bobr_Session::getInstance();
		$session->destroy();
		Bobr_Session::setNamesapce('validity', sha1(sha1($this->salt).sha1(self::$remoteAddr.sha1($this->publicKey))));
	}
}