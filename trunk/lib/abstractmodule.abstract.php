<?php
/**
 * Abstraktni trida pro Moduly
 *
 * Vzdy po zdedeni teto tridy je potreba volat rodicovsky
 * konstruktor, ktery naplni objekt zakladnimi daty.
 */
abstract class AbstractModule extends Object implements IModule
{
			// Konkretni blok ktery je volan
	private $block,
			// Prikaz, ktery se bude vykonavat
			$command,
			// Lokalizovane popisky blocku
			$description;

				// WebInstance
	protected	$webInstance = 'web';

	/**
	 * Toto se provadi vzdy a proto by to
	 * melo byt uniformni.
	 * Provedeni v modulu se zaridi volanim rodicovskeho konstruktoru
	 *
	 * @param $block array - Konkretni block, ktery mame vykonat
	 * @return void
	 */
	public function __construct( $block )
	{
		$this->block = $block;
		$this->command = $this->block['command'];

		$this->description = Description::getDescription( $this->block['description_id'] );

		$this->init();
	}

	/**
	 * Inicializacni funkce.
	 * Tato funkce se vola po zavolani konstruktoru
	 * @param void
	 * @return void
	 */
	protected function init()
	{
	}

	/**
	 * Loadne a vypise sablonu pro block
	 *
	 * @param void
	 * @return void
	 */
	public function output()
	{
		$fileName = 'modules/' . $this->command[0] . '/' . $this->webInstance . '/template/' . $this->command[1] . '.tpl';
		if( file_exists( $fileName ) ){
			require_once $fileName;
		}else{
			throw new TemplateException( "Nepodarilo se nacist sabonu: <b>$fileName</b>" );
		}
	}
	/**
	 * Vrati block
	 * @param void
	 * @return array
	 */
	public function getBlock()
	{
		return $this->block;
	}
	/**
	 * Vrati command
	 * @param void
	 * @return array
	 */
	public function getCommand()
	{
		return $this->command;
	}
	public function getDescription()
	{
		return $this->description;
	}
}