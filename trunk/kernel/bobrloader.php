<?php
require_once __DIR__ . '/kernel/autoloader.php';

class BobrLoader extends AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
		'object'	=>	'/kernel/object.php',
		'debug'		=>	'/lib/debug/debug.php',
		'bobr'		=>	'/kernel/bobr.php',
		'kernel'	=>	'/kernel/kernel.php',
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
		'database'	=>	'/lib/database.php',
		'cache'		=>	'/lib/cache.php',
		'dibi'		=>	'/lib/dibi/dibi.php',
		'pagehtml'		=>	'/lib/pagehtml.php',
		'log'		=>	'/lib/log.php',
		'formmaker'	=>	'/lib/formmaker.php',
		'api'		=>	'/lib/api.php',
		'ladenka'	=>	'/lib/ladenka.php',
		'message'	=>	'/lib/dataobject/message.php',
		'pageid'	=>	'/lib/dataobject/pageid.php',
		'block'		=>	'/lib/dataobject/block.php',
		'template'	=>	'/lib/dataobject/template.php',
		'description'	=>	'/lib/dataobject/description.php',
		'webinstance'	=>	'/lib/dataobject/webinstance.php',
		'module'	=>	'/lib/dataobject/module.php',
		'lang'		=>	'/lib/dataobject/lang.php',
		'administration'	=>	'/lib/dataobject/administration.php',

	);

/**
	 * Returns singleton instance with lazy instantiation.
	 * @return NetteLoader
	 */
	public static function getInstance()
	{
		if (self::$instance === NULL) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	/**
	 * Handles autoloading of classes or interfaces.
	 * @param  string
	 * @return void
	 */
	public function tryLoad($type)
	{
		$type = strtolower($type);
		if (isset($this->list[$type])) {
			self::includeOnce($this->base . $this->list[$type]);
			self::$count++;
		}
	}
}