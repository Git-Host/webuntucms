<?php
class Users_Module{
	private $output		= '';
	private $cnt		= 0;
	private $command	= array();
	private $position	= '';
	private $html		= FALSE;
	
	
	/**
	 * Vezme si objekt HTML.
	 * Nastavi si command, ktery prisel z delegatoru ,
	 * zjisti pozici (admin | web | blog | ...),
	 * ubere z commandu prvni pole (sve volani)
	 * a zavola metodu ktera mu prisla
	 *
	 * @param unknown_type $command
	 */
	public function __construct( $command ) {
		
		
		unset( $command[0] );
		$this->command = $command;
		$this->position = API::getProcessMethod();
		
		$method = $this->nextMethod($this->command);
		$this->$method($this->command);
	}
	
	/**
	 * odnastavi polozku z commandu a nastavi metodu ktera se ma zavolat
	 *
	 * @param array $command
	 * @return method name
	 */
	public function nextMethod( $command ) 
	{
		return is_array($command) ? array_shift($this->command) : FALSE;
	}
	
	public function show( $command ) 
	{
		$method = $this->nextMethod( $command );
		$this->output.=	HTML::tag( 'h1', "Users->show($command[0]) - BOBR PICO :D" );
		
		$result = dibi::query( "SELECT nick, email FROM " . BobrConf::DB_PREFIX . "users ORDER BY nick ASC" );
		$result = $result->fetchAll();
		
		$this->output .= '<div id="users-' . $method . '"><ul>';
		foreach( $result as $key => $val ) {
			$this->output .= HTML::tag( 'li', HTML::ahref( '/uzivatele', $val ) );	
		}
		$this->output .= '</ul></div>';
	}
	
	public function showAdmin( $command ) {
		$method = $this->nextMethod( $command );
		$this->output.=	HTML::tag( 'h1', "Users->show($command[0]) - BOBR PICO :D" );
		
		$result = dibi::query( "SELECT id,nick, email FROM " . BobrConf::DB_PREFIX . "users ORDER BY nick ASC" );
		$result = $result->fetchAll();
		
		$this->output .= '<div id="users-' . $method . '"><ul>';
		foreach( $result as $key => $val ) {
			$this->output .= HTML::tag( 'li', HTML::ahref( '/uzivatele', $val ) );	
		}
		$this->output .= '</ul></div>';
	}
	
	
	public function getHtmlOutput() {
		return $this->output;
	}
}
?>