<?php

// Nactem si vsechny vyjimky.
require_once __WEB_ROOT__ . '/Bobr/User/exception.php';
require_once __WEB_ROOT__ .  '/Bobr/Page/exception.php';


/**
 * Nejvissi vyjimka v BOBRovi.
 */
class Bobr_KernelException extends Exception{}
class Bobr_IAException extends Bobr_KernelException {}

/**
 * Vyjimka ktera se vyhazuje v pripade ze se nemuze nacist kernel.
 */
class Bobr_BobrException extends Bobr_KernelException {}
/**
 * Invalid Argument Exception
 */
class Bobr_BobrlIAException extends Bobr_BobrException {}

class Bobr_DataObjectException extends Bobr_BobrException {}
/**
 * Invalid Argument Exception
 */
class Bobr_DataObjectIAException extends Bobr_DataObjectException {}

class Bobr_ColectionException extends Bobr_BobrException {}
/**
 * Invalid Argument Exception
 */
class Bobr_ColectionIAException extends Bobr_ColectionException {}

class Bobr_ProcessException extends Bobr_BobrException {}
class Bobr_ProcessIAException extends Bobr_ProcessException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit modul
 */
class Bobr_ModuleException extends Bobr_BobrException{}

/**
 * Nove vyjimky
 * @author rbas
 *
 */
class Bobr_Exception extends Exception {}
class Bobr_Access_Exception extends Bobr_Exception {}