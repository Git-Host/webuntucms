<?php
class Addresser_Module 
{
	public static $className = __CLASS__;
	public $output;
	
	public function __construct( $method ) {
			Ladenka::print_re( 'jsi v modulu aresar' );
			Ladenka::print_re($method);
	}
	
	public static function showWeb() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<h2>JA JSEM MOCNY ADRESAR a ukazu se ti na WEBU<h2>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	public static function editWeb() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<h2>JA JSEM MOCNY ADRESAR a ukazu se ti na WEBU<h2>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	
	public static function showAdmin() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<h2>Administrator MOCNEHO ADRESARE<h2>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	public static function newOneAdmin() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<form action="" method="post" name="addresserFrm">
						<h2>JA JSEM MOCNY ADRESAR</h2>
						<input type="text" name="title" value="Zadejte nazev noveho adresare" />
						<input type="submit" name="submitNew" value="Vlozit" />
					</form>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	public static function newItemAdmin() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<form action="" method="post" name="addresserFrm">
						<h2>JA JSEM MOCNY ADRESAR</h2>
						<input type="text" name="title" value="Zadejte nazev noveho adresare" />
						<input type="submit" name="submitNew" value="Vlozit" />
					</form>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	public static function editAdmin() {
		
		$HTML = HTML::getSingleton();
		
		$show = 
				'<div id="addresser">
					<h2>JA JSEM MOCNY ADRESAR a ukazu se ti na Administraci<h2>
				</div>
				<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
				';
		
		$HTML->addOutput( $show );		
		
	}
	
	
	public function getHtmlOutput()
	{
		$output = $this->output;
		$this->output = '';
		return $output;
	}
}
?>