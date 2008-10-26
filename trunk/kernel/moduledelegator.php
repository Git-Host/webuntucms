<?php
class ModuleDelegator extends Object
{
	private static $instance = FALSE,
				   $selfName= __CLASS__;

	// Objekty
	protected 	$validator,
				$module;

	protected 	$webInstance,
				// GetCommandem se mysli klasicky command, ale tento prisel z getu
				$getCommand,
				$command;


	public static function getSingleton( $webInstance, $getCommand ) {
		if(FALSE == self::$instance) {
			return self::$instance = new self::$selfName( $webInstance, $getCommand );
		} else {
			return self::$instance;
		}
	}

	private function __construct( $webInstance, $getCommand )
	{
		$this->validator = Validator::getSingleton();
		$this->module = Module::getSingleton();
		$this->webInstance = $webInstance;
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
			$className = $block['module'] . $this->webInstance;
			$fileName = __DIR__ . '/modules/' . $block['module'] . '/' . $this->webInstance . '/' . $block['module'] . '.' . $this->webInstance . '.php';

			if( file_exists( $fileName ) ){
				try{
					if( FALSE === class_exists( $className ) ){
						require_once $fileName;
					}
					// protoze jsem si jiz command rozparsovali tak ho priradime do pole at to nedelame 100x
					$block['command'] = $this->command;
					$module = new $className( $block );
					$module->output();
				}catch ( TemplateException $exception ){
					Message::addFatal( $exception->getMessage() );
				}
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