<?php

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
 * @version    $Id: InstanceFilterIterator.php 110 2008-11-10 14:10:29Z david@grudl.com $
 */

/*namespace Nette;*/



/**
 * Instance iterator filter.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette
 */
class InstanceFilterIterator extends /*\*/FilterIterator
{
	/** @var string */
	private $type;


	/**
	 * Constructs a filter around another iterator.
	 * @param  Iterator
	 * @param  string  class/interface name
	 */
	public function __construct(/*\*/Iterator $iterator, $type)
	{
		/**/// fix for namespaced classes/interfaces in PHP < 5.3
		if ($a = strrpos($type, '\\')) $type = substr($type, $a + 1);/**/
		$this->type = $type;
		parent::__construct($iterator);
	}



	/**
	 * Expose the current element of the inner iterator?
	 * @return bool
	 */
	public function accept()
	{
		return $this->current() instanceof $this->type;
	}

}
