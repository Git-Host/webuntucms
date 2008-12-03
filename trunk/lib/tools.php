<?php

class Tools
{
	private static $bootWebInstance;


	/**
	 * Vyrizne ze script name nazev
	 * @todo provadet kontrolu oproti databazovym webInstancim jestli vubec takova existuje
	 *
	 * @return string $matches[1] vrati nazev scriptu oriznute o php
	 */
	public static function getWebInstance()
	{
		if( empty( self::$bootWebInstance ) ){
			preg_match( "/(\w*)\.php/", $_SERVER['SCRIPT_NAME'], $matches );
			if( isset ( $matches[1] ) ){
				self::$bootWebInstance = $matches[1];
				return self::$bootWebInstance;
			}else {
				throw new Exception("Nelze identifikovat spoustejici script.");
			}
		}else {
			return self::$bootWebInstance;
		}

	}
}