<?php

// Nactem si vsechny vyjimky.
require_once __WEB_ROOT__ . '/Kernel/User/exception.php';
require_once __WEB_ROOT__ .  '/Kernel/Page/exception.php';


/**
 * Nejvissi vyjimka v BOBRovi.
 */
class Kernel_KernelException extends Exception{}

/**
 * Vyjimka ktera se vyhazuje v pripade ze se nemuze nacist kernel.
 */
class Kernel_BobrException extends Kernel_KernelException {}
/**
 * Invalid Argument Exception
 */
class Kernel_BobrlIAException extends Kernel_BobrException {}

class Kernel_DataObjectException extends Kernel_BobrException {}
/**
 * Invalid Argument Exception
 */
class Kernel_DataObjectIAException extends Kernel_DataObjectException {}

class Kernel_ColectionException extends Kernel_BobrException {}
/**
 * Invalid Argument Exception
 */
class Kernel_ColectionIAException extends Kernel_ColectionException {}

class Kernel_ProcessException extends Kernel_BobrException {}
class Kernel_ProcessIAException extends Kernel_ProcessException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit modul
 */
class Kernel_ModuleException extends Kernel_BobrException{}