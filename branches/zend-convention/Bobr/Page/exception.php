<?php

/**
 * Nejvissi vyjimka kterou muzou vyhodit tridy v PAGE
 */
class Bobr_Page_PageException extends Bobr_BobrException {}
/**
 * Invalid Argument Exception
 */
class Bobr_Page_PageIAException extends Bobr_Page_PageException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida PageId.
 */
class Bobr_Page_PageIdException extends Bobr_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Bobr_Page_PageIdIAException extends Bobr_Page_PageIdException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida ContainerColectionException.
 */
class Bobr_Page_ContainerColectionException extends Bobr_Page_PageException{}
/**
 * Invalid Argument Exception
 */
class Bobr_Page_ContainerColectionIAException extends Bobr_Page_ContainerColectionException{}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Container.
 */
class Bobr_Page_ContainerException extends Bobr_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Bobr_Page_ContainerIAException extends Bobr_Page_ContainerException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Block.
 */
class Bobr_Page_BlockException extends Bobr_Page_PageException {}
/**
 * Invalid Argument Exception
 */
class Bobr_Page_BlockIAException extends Bobr_Page_BlockException {}

class Bobr_Page_TemplateException extends Bobr_Page_PageException {}