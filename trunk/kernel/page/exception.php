<?php

/**
 * Nejvissi vyjimka kterou muzou vyhodit tridy v PAGE
 */
class Kernel_Page_PageException extends Kernel_BobrException {}
/**
 * Invalid Argument Exception
 */
class Kernel_Page_PageIAException extends Kernel_Page_PageException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida PageId.
 */
class Kernel_Page_PageIdException extends Kernel_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Kernel_Page_PageIdIAException extends Kernel_Page_PageIdException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida ContainerColectionException.
 */
class Kernel_Page_ContainerColectionException extends Kernel_Page_PageException{}
/**
 * Invalid Argument Exception
 */
class Kernel_Page_ContainerColectionIAException extends Kernel_Page_ContainerColectionException{}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Container.
 */
class Kernel_Page_ContainerException extends Kernel_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Kernel_Page_ContainerIAException extends Kernel_Page_ContainerException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Block.
 */
class Kernel_Page_BlockException extends Kernel_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Kernel_Page_BlockIAException extends Kernel_Page_BlockException {}

class Kernel_Page_TemplateException extends Kernel_Page_PageException {}