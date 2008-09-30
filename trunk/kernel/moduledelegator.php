<?php
class ModuleDelegator extends Object
{
	private static $instance = FALSE,
				   $selfName= __CLASS__;
	
	// Objekty
	protected 	$validator,
				$module;
				
	protected 	$webInstace,
				// GetCommandem se mysli klasicky command, ale tento prisel z getu
				$getCommand,
				$command;

	
	public static function getSingleton( $webInstace, $getCommand ) {
		if(FALSE == self::$instance) {
			return self::$instance = new self::$selfName( $webInstace, $getCommand );
		} else {
			return self::$instance;
		}
	}
	
	private function __construct( $webInstace, $getCommand )
	{
		$this->validator = Validator::getSingleton();
		$this->module = Module::getSingleton();
		$this->webInstace = $webInstace;
		$this->getCommand = $getCommand;
	}
	
	public function loadModule( $block )
	{
		if( empty( $block['command'] ) ){
			$this->command = $this->getCommand;
		}else{
			$this->command = explode('/', $block['command']); 
		}
		if( TRUE ===  $this->validateCommand() ){
			$className = $block['module'] . $this->webInstace;
			$fileName = __DIR__ . '/modules/' . $block['module'] . '/' . $this->webInstace . '/' . $block['module'] . '.' . $this->webInstace . '.php';
			
			if( file_exists( $fileName ) ){
				if( FALSE === class_exists( $className ) ){
					require_once $fileName;
				}
				// protoze jsem si jiz command rozparsovali tak ho priradime do pole at to nedelame 100x
				$block['command'] = $this->command;
				$module = new $className( $block );
				$module->output();
			}else {
				Message::addFatal( "Byl volan neexistujici modul.<br />" . $block['command'] );
			}
		}else {
			Message::addError('Nemate pravo pro pouziti funkce.');
		}
	}
	
	/**
	 * Zjisti jestli volana funkce je mezi dostupnymi
	 * pro daneho uzivatele.
	 */
	private function validateCommand() {
		$hash = API::commandToHash( $this->command );
		return array_key_exists( $hash, $this->validator->groupFunctions );
	}
}