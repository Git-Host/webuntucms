<?php
/**
 * Zde se definuji vyjimky
 * 
 * Pokud je potreba v nejake vyjimce upravovat metody apod.
 * jsou ve vlastnim souboru pod jmenem nazev.exception.php
 * 		!!! Pokud takova vyjimka existuje musi se zde requrnout !!!!
 * 			Autoload sem nesaha!!!!
 */

require_once __DIR__ . "/lib/Nette/Debug.php";

/**
 * Chyby pri loadovani trid
 *
 */
class LoaderException extends LogicException{}
/**
 * Vyjimka pro jadro
 */
class KernelException	extends Exception {
	function __construct ( $message )
	{
		parent::__construct( $message );
	}
}
/**
 * Pri prazi s cachi muzou nastat vyjimky
 *
 */
class CacheException extends LogicException{}
/**
 * Vyjimky pro url
 */
class UrlException extends KernelException {}
/**
 * Pri nevalidnich pozadavcich pres xmlHttpRequest
 * se vyvola tato vyjimka
 */
class AjaxException extends Exception {}
class BlockException extends KernelException {}
class TemplateException extends BlockException{}

class FatalException extends Exception {}
// Pokud je neco spatneho s hlavickama...
class HeaderException	extends Exception {}
// Pokud neni nalezena pozadovana soubor/trida | interface | modul | abstraktni trida | lib 
class ClassException	extends KernelException {}
// Problem v popiskovaci tride
class DescriptionException extends LogicException {}

class CreatePageException extends KernelException {}