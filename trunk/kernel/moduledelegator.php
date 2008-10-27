<?php
class ModuleDelegator extends Object
{
	private static $instance = FALSE,
				   $selfName= __CLASS__;

	// Objekty
	protected 	$validator,
				$module;

	protected 	$webInstance,
				// GETCommandem se mysli klasicky command, ale tento prisel z getu
				$GETCommand,
				$command,
				$groupFunctionsList;


	public static function getSingleton( $webInstance, $GETCommand ) {
		if(FALSE == self::$instance) {
			return self::$instance = new self::$selfName( $webInstance, $GETCommand );
		} else {
			return self::$instance;
		}
	}

	private function __construct( $webInstance, $GETCommand )
	{
		$this->validator = Validator::getSingleton();
		$this->module = Module::getSingleton();
		$this->webInstance = $webInstance;
		$this->GETCommand = $GETCommand;
		$this->setGroupFunctionsList();
	}

	public function loadModule( $block )
	{
		// Kdyz block nema prirazeny command chce byt obvladan z GETu
		if( empty( $block['command'] ) ){
			$this->command = $this->GETCommand;
		}else{
			$this->command = explode('/', $block['command']);
		}
		if( TRUE ===  $this->validateCommand() ){
			// Vytvorime si jemeno tridy s ohledem na web instanci
			$className = $block['module'] . $this->webInstance;
			$fileName = __DIR__ . '/modules/' . $block['module'] . '/' . $this->webInstance . '/' . $block['module'] . '.' . $this->webInstance . '.php';

			if( file_exists( $fileName ) ){
				try{
					if( FALSE === class_exists( $className ) ){
						require_once $fileName;
					}
					// Priradime do blocku rozparsovany command (tedy pole)
					$block['command'] = $this->command;
					// Vitvorime objekt a vlozime do nej block
					$module = new $className( $block );
					// Zavolame vypisovaci metodu, tato metoda jiz vypisuje do bufferu php (tedy echo bo neco podobneho)
					$module->output();

				}catch ( TemplateException $exception ){
					Message::addFatal( $exception->getMessage() );
				}
			}else {
				// @todo zapisovat do nejakeho error logu
				Message::addFatal( "Byl volan neexistujici modul.<br />" . $block['command'] );
			}
		}else {
			// @todo umoznit vypnout tyto hlasky pomoci administrace
			Message::addError('Nemate pravo pro pouziti funkce.');
		}
	}

	/**
	 * Zjisti jestli volana funkce je mezi dostupnymi
	 * pro daneho uzivatele.
	 */
	private function validateCommand() {
		if( isset( $this->groupFunctionsList[ $this->command[0] ] ) ){
			// Projedem si funkce modulu
			// @todo mozna by to slo resit lepe
			foreach( $this->groupFunctionsList[ $this->command[0] ] as $function){
				// Zvalidujeme jestli ma pravo na danou funkcnost
				if( preg_match( '@'. $this->command[0] . '/' . $function['func'] .'@', implode( '/', $this->command ) ) ){
					return TRUE;
				}
			}
		}
		return FALSE;
	}

	private function setGroupFunctionsList()
	{
		if( empty( $this->groupFunctionsList ) ){
			return $this->groupFunctionsList =  $this->validator->groupFunctions;
		}else{
			return $this->groupFunctionsList;
		}
	}
}