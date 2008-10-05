<?php
	require_once __DIR__ . '/kernel/exceptions/exceptions.php';
	require_once __DIR__ . '/lib/Nette/loader.php';
	try{
		// Nastavime kodovani serveru pro iconv
		$config = BobrConf::getSingleton();
		
		if( TRUE === BobrConf::DEBUG_MODE ){
			Debug::enable( E_ALL | E_STRICT | E_NOTICE , FALSE );
		}
		
		// Spojime se s databazi
		try {
			Database::connectionToPsqlDatabase( BobrConf::DB_CONNECTION_NAME );
		}catch ( DibiDriverException $e){
			// @todo vyhodit nejakou peknou stranku s omluvou, ze se nemuzu pripojit do DB
			Debug::dump( $e );
		}
		// Nejake ty susenky a validace
		$session = Session::getSingleton();
		$validator = Validator::getSingleton();
		// A zacneme se hejbat... podivame se na zoubek url
		$processMethod = Api::getProcessMethod();
		if( FALSE === $validator->processAccess( $processMethod ) ){
			Message::addError( 'Nemate dostatecna prava pro vstup na tuto stranku.');
			Request::redirect( $config['WEB_ROOT'] );
		}else {
			$processWebName = 'Process' . Api::getProcessMethod();
			$process = new $processWebName( $session );
			
			// Nastavime popiskovaci jazyk aby se pozdeji nemenil
			Description::setLang( $process->lang );
			
			// Mame potrebne data pro sestaveni stranky
			$createPage = new CreatePage( $process );
		}
		
		
	}catch ( KernelException $exception ){
		//@todo vykreslit neakou statickou stranku
		print $exception->getMessage() .'<br />';
		print $exception->getFile() .'<br />';
		print $exception->getLine() .'<br />';
		echo "<pre>";
			var_dump( $exception->getTrace() );
		echo "</pre>";
	}