<?php

// Nactem si vsechny vyjimky.
require_once __WEB_ROOT__ . '/kernel/user/exception.php';
require_once __WEB_ROOT__ .  '/kernel/page/exception.php';


/**
 * Nejvissi vyjimka v BOBRovi.
 */
class KernelException extends Exception{}

/**
 * Vyjimka ktera se vyhazuje v pripade ze se nemuze nacist kernel.
 */
class BobrException extends KernelException {}
/**
 * Invalid Argument Exception
 */
class BobrlIAException extends BobrException {}

class DataObjectException extends BobrException {}
/**
 * Invalid Argument Exception
 */
class DataObjectIAException extends DataObjectException {}

class ColectionException extends BobrException {}
/**
 * Invalid Argument Exception
 */
class ColectionIAException extends ColectionException {}

class ProcessException extends BobrException {}
class ProcessIAException extends ProcessException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit modul
 */
class ModuleException extends BobrException{}