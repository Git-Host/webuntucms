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
		'commandvalidator'	=>	'/kernel/commandvalidator.php',
		'sessionvalidator'	=>	'/kernel/sessionvalidator.php',
		'webinstancevalidator'	=> '/kernel/webinstancevalidator.php',
		'user'		=>	"/kernel/user/user.php",
		'uservalidator'	=> '/kernel/user/uservalidator.php',
		'groupslist'=>	'/kernel/user/groupslist.php',
		'group'		=>	'/kernel/user/group.php',
		'modulelist'=>	'/kernel/user/modulelist.php',
		'module'	=>	'/kernel/user/module.php',
		'functionslist'	=>	'/kernel/user/functionslist.php',
		'modulefunction'	=>	'/kernel/user/modulefunction.php',
		'webinstancelist'	=> '/kernel/user/webinstancelist.php',
		'webinstance'	=>	'/kernel/user/webinstance.php',
		'page'		=>	'/kernel/page/page.php',
		'csslist'	=>	'/kernel/page/csslist.php',
		'css'		=>	'/kernel/page/css.php',
		'containerlist'	=>	'/kernel/page/containerlist.php',
		'container'	=>	'/kernel/page/container.php',
		'blockslist'	=>	'/kernel/page/blockslist.php',
		'block'		=>	'/kernel/page/block.php',
		'javascriptlist'	=>	'/kernel/page/javascriptlist.php',
		'javascript'=>	'/kernel/page/javascript.php',
		'metataglist'	=> '/kernel/page/metataglist.php',
		'metatag'	=>	'/kernel/page/metatag.php',
		'lang'		=>	'/kernel/lang.php',
		'lib'		=>	'/lib/lib.php',
		'dibi'		=>	'/lib/dibi/dibi.php',
		'debug'		=>	'/lib/debug/debug.php',
		'sample'	=>	'/sample.php',
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