<?php
/**
 * Rozhrani pro vytvareni modulu.
 * 
 * Toto rozhrani pouziva AbstractModule,
 * kde jsou castecne implemetovany metody pro praci s modulama.
 * Lze vsak pouzit rozhrani i do jine tridy, za predpokladu dodrzeni
 * vsech nalezitosti.
 */
interface IModule
{
	
	/**
	 * Do konstruktoru vkladame block,
	 * ktery budeme vypisovat
	 *
	 * @param array $block
	 */
	function __construct( $block );
		
	/**
	 * Loadne template pro block
	 * Tato metoda je volana vzdy po volani konstruktoru,
	 * Proto je nezbytna jeji implementace.
	 * Ocekava se, ze vypisuje nactenou template i s daty.
	 * 
	 * Z pravidla provadi require_once 'nejaka_template.tpl';
	 *	
	 * @return void
	 */
	function output();
	
	
}