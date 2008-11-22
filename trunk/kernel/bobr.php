<?php

class Bobr extends Object
{

	public function run()
	{
		$this->debug();
		$this->connectToDatabase();
		$this->getBobr();
	}

	/**
	 * DibiDriverException
	 */
	private function connectToDatabase()
	{
		$config = new Config;
		$connect = dibi::connect(array(
			'driver'     => 'postgre',
			'string'     => ' host='	. $config->dbHost .
							' port=' 	. $config->dbPort .
							' dbname='	. $config->dbName .
							' user='	. $config->dbUser .
							' password='. $config->dbPassword . '',
			'persistent' => $config->dbPersistent,
		), $config->dbConnectionName );
	}

	private function debug()
	{
		$config = new Config;
		if( TRUE === $config->debugMode ){
			Debug::enable( E_ALL | E_STRICT | E_NOTICE , FALSE );
		}
	}

	private function getBobr()
	{
		// Zvalidujem platnost Session
		new SessionValidator();
		$validator = new UserValidator();
		// Zvalidujem uzivatele v session
		if(FALSE === $validator->validate()){
			// Uzivatel nebyl validni nastavime anonymouse
			Session::getInstance()->user = new User;
			echo '<p>Nastavil jsem Anonymouse.</p>';
		}else{
			echo '<p>Uzivatel mel jiz vytvorenou session.</p>';
		}
		$user = Session::getInstance()->user;

		$webInstanceValidatdor = new WebInstanceValidator();
		if (TRUE === $webInstanceValidatdor->validate(Lib::getWebInstance())) {
			echo '<p>Uzivatel ma pristup na tuto web instanci</p>';
		} else {
			echo '<p>Uzivatel NEMA pristup na tuto web instanci</p>';
		}
	}
}