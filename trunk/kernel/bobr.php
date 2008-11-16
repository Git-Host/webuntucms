<?php
class Bobr extends Object {

	public function run()
	{
		// Nastavime kodovani serveru pro iconv
		BobrConf::getInstance();
		$this->debug();
		$this->connectToDatabase();
		$this->getBOBR();
	}

	private function debug()
	{
		if( TRUE === BobrConf::DEBUG_MODE ){
			Debug::enable( E_ALL | E_STRICT | E_NOTICE , FALSE );
		}
	}

	private function connectToDatabase()
	{
		// Spojime se s databazi
		try {
			Database::connectionToPsqlDatabase( BobrConf::DB_CONNECTION_NAME );
		}catch ( DibiDriverException $e){
			// @todo vyhodit nejakou peknou stranku s omluvou, ze se nemuzu pripojit do DB
			Debug::dump( $e );
		}
	}

	private function getBobr()
	{
		// Nejake ty susenky a validace
		$session = Session::getInstance();
		$validator = Validator::getInstance();
		// A zacneme se hejbat... podivame se na zoubek url
		$processMethod = Api::getProcessMethod();
		if( FALSE === $validator->processAccess( $processMethod ) ){
			Message::addError( 'Nemate dostatecna prava pro vstup na tuto stranku.');
			//var_dump($_SESSION);
			//die();
			Request::redirect( $config['WEB_ROOT'] );
		}else {
			$processWebName = 'Process' . Api::getProcessMethod();
			$process = new $processWebName( $session );

			// Nastavime popiskovaci jazyk aby se pozdeji nemenil
			Description::setLang( $process->lang );

			// Mame potrebne data pro sestaveni stranky
			$createPage = new CreatePage( $process );
		}
	}

	public function __destruct()
	{
		Session::getInstance()->saveSession();
	}
}