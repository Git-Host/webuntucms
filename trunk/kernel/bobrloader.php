<?php
require_once __WEB_ROOT__ . '/kernel/autoloader.php';

class BobrLoader extends AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
		'bobr'		=>	'/kernel/bobr.php',
		'object'	=>	'/kernel/object.php',
		'bobrloader'=>	'/kernel/bobrloader.php',
		'autoloader'=>	'/kernel/autoloader.php',
		'config'	=>	'/kernel/config/localconfig.php',
		'defaultconfig' => '/kernel/config/defaultconfig.php',
		'indexconfig'	=> '/kernel/config/indexconfig.php',
		'adminconfig'	=> '/kernel/config/adminconfig.php',
		'session'	=>	'/kernel/session.php',
		'sessionvalidator'	=>	'/kernel/sessionvalidator.php',
		'user'		=>	"/kernel/user.php",
		'uservalidator'	=> '/kernel/uservalidator.php',
		'groupslist'=>	'/kernel/groupslist.php',
		'modulelist'=>	'/kernel/modulelist.php',
		'module'	=>	'/kernel/module.php',
		'functionslist'	=>	'/kernel/functionslist.php',
		'modulefunction'	=>	'/kernel/modulefunction.php',
		'webinstancelist'	=> '/kernel/webinstancelist.php',
		'webinstance'	=>	'/kernel/webinstance.php',
		'group'		=>	'/kernel/group.php',
		'lib'		=>	'/lib/lib.php',
		'dibi'		=>	'/lib/dibi/dibi.php',
		'debug'		=>	'/lib/debug/debug.php',
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