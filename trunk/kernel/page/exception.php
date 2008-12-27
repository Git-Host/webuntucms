<?php

/**
 * Nejvissi vyjimka kterou muzou vyhodit tridy v PAGE
 */
class PageException extends BobrException {}
/**
 * Invalid Argument Exception
 */
class PageIAException extends PageException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida PageId.
 */
class PageIdException extends PageException {}
/**
 * Invalid Argument Exception
 */
class PageIdIAException extends PageIdException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida ContainerColectionException.
 */
class ContainerColectionException extends PageException{}
/**
 * Invalid Argument Exception
 */
class ContainerColectionIAException extends ContainerColectionException{}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Container.
 */
class ContainerException extends PageException {}
/**
 * Invalid Argument Exception
 */
class ContainerIAException extends ContainerException {}

/**
 * Nejvissi vyjimka kterou muze vyhodit trida Block.
 */
class BlockException extends PageException {}
/**
 * Invalid Argument Exception
 */
class BlockIAException extends BlockException {}

class TemplateException extends PageException {}