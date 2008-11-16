<?php
/**
 * Zde se definuji vyjimky
 *
 * Pokud je potreba v nejake vyjimce upravovat metody apod.
 * jsou ve vlastnim souboru pod jmenem nazev.exception.php
 * 		!!! Pokud takova vyjimka existuje musi se zde requrnout !!!!
 * 			Autoload sem nesaha!!!!
 */

require_once __DIR__ . "/lib/debug/debug.php";

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


/**
 * Nette Framework
 *
 * Copyright (c) 2004, 2008 David Grudl (http://davidgrudl.com)
 *
 * This source file is subject to the "Nette license" that is bundled
 * with this package in the file license.txt.
 *
 * For more information please see http://nettephp.com
 *
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @license    http://nettephp.com/license  Nette license
 * @link       http://nettephp.com
 * @category   Nette
 * @package    Nette
 * @version    $Id: exceptions.php 34 2008-07-17 04:29:58Z David Grudl $
 */

// no namespace



/*
some useful SPL exception:

- LogicException
	- InvalidArgumentException
	- LengthException
- RuntimeException
	- OutOfBoundsException
	- UnexpectedValueException

other SPL exceptions are ambiguous; do not use them
*/
/**
 * The exception that is thrown when the value of an argument is
 * outside the allowable range of values as defined by the invoked method.
 * @package    Nette
 */
class ArgumentOutOfRangeException extends InvalidArgumentException
{
}
/**
 * The exception that is thrown when a method call is invalid for the object's
 * current state, method has been invoked at an illegal or inappropriate time.
 * @package    Nette
 */
class InvalidStateException extends RuntimeException
{
}
/**
 * The exception that is thrown when a requested method or operation is not implemented.
 * @package    Nette
 */
class NotImplementedException extends LogicException
{
}
/**
 * The exception that is thrown when an invoked method is not supported. For scenarios where
 * it is sometimes possible to perform the requested operation, see InvalidStateException.
 * @package    Nette
 */
class NotSupportedException extends LogicException
{
}
/**
 * The exception that is thrown when accessing a class member (property or method) fails.
 * @package    Nette
 */
class MemberAccessException extends LogicException
{
}
/**
 * The exception that is thrown when an I/O error occurs.
 * @package    Nette
 */
class IOException extends RuntimeException
{
}
/**
 * The exception that is thrown when accessing a file that does not exist on disk.
 * @package    Nette
 */
class FileNotFoundException extends IOException
{
}
/**
 * The exception that is thrown when part of a file or directory cannot be found.
 * @package    Nette
 */
class DirectoryNotFoundException extends IOException
{
}
/**
 * The exception that indicates errors that can not be recovered from. Execution of
 * the script should be halted.
 * @package    Nette
 */
class FatalErrorException extends ErrorException
{
}