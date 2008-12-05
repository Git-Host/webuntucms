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
            Messanger::addNote('Nastavil jsem anonymouse.');
			//echo '<p>Nastavil jsem Anonymouse.</p>';
		}else{
            Messanger::addNote('Uzivatel mel jiz vytvorenou session.');
			//echo '<p>Uzivatel mel jiz vytvorenou session.</p>';
		}
		$user = Session::getInstance()->user;

		$webInstanceValidatdor = new WebInstanceValidator();
		if (TRUE === $webInstanceValidatdor->validate(Tools::getWebInstance())) {
            Messanger::addNote('Uzivatel ma pristup na tuto web instanci.');
			//echo '<p>Uzivatel ma pristup na tuto web instanci</p>';
		} else {
            Messanger::addNote('Uzivatel NEMA pristup na tuto web instanci.');
			//echo '<p>Uzivatel NEMA pristup na tuto web instanci</p>';
            // @todo presmerovavat nekam s nejakou hlaskou.
		}
        
        $process = new Process;
        print_re($process);
        if (0 < $process->pageId) {
            $page = new Page($process->pageId);
            print_Re($page);
        }

        Messanger::addNote('Je mozne prejit na statickou url /prihlaseni nebo dynamickou /ukaz-clanek/cislo/15 cislo 15 je promene');
        Messanger::flush();
	}
}