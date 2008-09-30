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
 * @package    Nette::Loaders
 * @version    $Id: NetteLoader.php 35 2008-07-17 11:36:16Z David Grudl $
 */

/*namespace Nette::Loaders;*/


require_once __DIR__ . '/lib/Nette/Loaders/AutoLoader.php';



/**
 * Nette auto loader is responsible for loading Nette classes and interfaces.
 *
 * @author     David Grudl
 * @copyright  Copyright (c) 2004, 2008 David Grudl
 * @package    Nette::Loaders
 */
class NetteLoader extends AutoLoader
{
	public $base;

	public $list = array(
		'directorynotfoundexception' 	=> '/lib/Nette/exceptions.php',
		'idebuggable'	=>	'/lib/Nette/IDebuggable.php',
		'environment'	=>	'/lib/Nette/Environment.php',
		'servicelocator'=>	'/lib/Nette/ServiceLocator.php',
		'iseviceLocator'=>	'/lib/Nette/IServiceLocator.php',
		'bobrconf'	=>	'/kernel/config/config.php',
		'settings'	=>	'/kernel/config/settings.php',
		'request'	=>	'/kernel/request.php',
		'processweb'=>	'/kernel/processweb.abstract.php',
		'processindex'	=> '/kernel/processindex.php',
		'processadmin'	=> '/kernel/processadmin.php',
		'createpage'=>	'/kernel/createpage.php',
		'core'		=>	'/kernel/core.php',
		'session'	=>	'/kernel/session.php',
		'user'		=>	'/kernel/user.php',
		'validator'	=>	'/kernel/validator.php',
		'moduledelegator'	=> '/kernel/moduledelegator.php',
		'imodule'	=>	'/lib/imodule.php',
		'abstractmodule'	=>	'/lib/abstractmodule.abstract.php',
		'administration'	=>	'/lib/administration.php',
		'database'	=>	'/lib/database.php',
		'cache'		=>	'/lib/cache.php',
		'object'	=>	'/lib/Nette/Object.php',
		'dibi'		=>	'/lib/dibi/dibi.php',
		'framework'	=>	'/lib/Nette/Framework.php',
		'debug'		=>	'/lib/Nette/Debug.php',
		'module'	=>	'/lib/module.php',
		'lang'		=>	'/lib/lang.php',
		'html'		=>	'/lib/html.php',
		'log'		=>	'/lib/log.php',
		'formmaker'	=>	'/lib/formmaker.php',
		'message'	=>	'/lib/message.php',
		'pageid'	=>	'/lib/pageid.php',
		'api'		=>	'/lib/api.php',
		'description'	=>	'/lib/description.php',
		'ladenka'	=>	'/lib/ladenka.php',
		'block'		=>	'/lib/block.php',
		'template'	=>	'/lib/template.php',
	);



	/**
	 * Handles autoloading of classes or interfaces.
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		/**/// fix for namespaced classes/interfaces in PHP < 5.3
		if ($a = strrpos($type, ':')) $type = substr($type, $a + 1);/**/

		$type = strtolower($type);
		if (isset($this->list[$type])) {
			self::includeOnce($this->base . $this->list[$type]);
			self::$count++;
		}
	}



	/**
	 * Autoloader factory.
	 * @return NetteLoader
	 */
	public static function create()
	{
		$loader = new self;
		$loader->base = __DIR__;
		$loader->register();
		return $loader;
	}

}
