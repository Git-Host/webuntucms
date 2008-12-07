<?php
require_once __WEB_ROOT__ . '/kernel/AutoLoader.php';

class BobrLoader extends AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
		'bobr'		=>	'/kernel/Bobr.php',
		'object'	=>	'/kernel/Object.php',
		'bobrloader'=>	'/kernel/BobrLoader.php',
		'autoloader'=>	'/kernel/AutoLoader.php',
		'config'	=>	'/kernel/config/localconfig.php',
        'abstractconfig' => '/kernel/config/AbstractConfig.php',
		'defaultconfig' => '/kernel/config/defaultconfig.php',
		'indexconfig'	=> '/kernel/config/indexconfig.php',
		'adminconfig'	=> '/kernel/config/adminconfig.php',
		'session'	=>	'/kernel/Session.php',
		'commandvalidator'	=>	'/kernel/CommandValidator.php',
		'sessionvalidator'	=>	'/kernel/SessionValidator.php',
		'webinstancevalidator'	=> '/kernel/WebInstanceValidator.php',
		'user'		=>	"/kernel/user/User.php",
		'uservalidator'	=> '/kernel/user/UserValidator.php',
		'groupslist'=>	'/kernel/user/GroupsList.php',
		'group'		=>	'/kernel/user/Group.php',
		'modulelist'=>	'/kernel/user/ModuleList.php',
		'module'	=>	'/kernel/user/Module.php',
		'functionslist'	=>	'/kernel/user/FunctionsList.php',
		'modulefunction'	=>	'/kernel/user/ModuleFunction.php',
		'webinstancelist'	=> '/kernel/user/WebInstanceList.php',
		'webinstance'	=>	'/kernel/user/WebInstance.php',
        'template'  =>  '/kernel/page/Template.php',
        'pagebuilder'   =>  '/kernel/page/PageBuilder.php',
		'page'		=>	'/kernel/page/Page.php',
		'csslist'	=>	'/kernel/page/CssList.php',
		'css'		=>	'/kernel/page/Css.php',
		'containerlist'	=>	'/kernel/page/ContainerList.php',
		'container'	=>	'/kernel/page/Container.php',
		'blockslist'	=>	'/kernel/page/BlocksList.php',
		'block'		=>	'/kernel/page/Block.php',
		'javascriptlist'	=>	'/kernel/page/JavascriptList.php',
		'javascript'=>	'/kernel/page/Javascript.php',
		'metataglist'	=> '/kernel/page/MetatagList.php',
		'metatag'	=>	'/kernel/page/Metatag.php',
		'httprequest'	=>	'/kernel/request/HttpRequest.php',
		'httpproperty'	=>	'/kernel/request/HttpProperty.php',
		'ihttpproperty'	=>	'/kernel/request/IHttpProperty.php',
		'httpget'		=>	'/kernel/request/HttpGet.php',
		'httppost'		=>	'/kernel/request/HttpPost.php',
		'httpcookie'	=>	'/kernel/request/HttpCookie.php',
		'httpfile'	=>	'/kernel/request/HttpFile.php',
		'httpheader'=>	'/kernel/request/HttpHeader.php',
		'file'		=>	'/kernel/request/File.php',
        'process'   =>  '/kernel/Process.php',
        'route'     =>  '/kernel/request/Route.php',
        'dynamicroute'  =>  '/kernel/request/DynamicRoute.php',
		'lang'		=>	'/kernel/Lang.php',
        'langlist'  =>  '/kernel/LangList.php',
		'tools'		=>	'/lib/Tools.php',
		'dibi'		=>	'/lib/dibi/dibi.php',
		'debug'		=>	'/lib/debug/debug.php',
		'sample'	=>	'/sample.php',
        'cache'     =>  '/kernel/cache/Cache.php',
        'dataobject' => '/kernel/cache/DataObject.php',
        'idataobject' => '/kernel/cache/IDataObject.php',
        'icacheadapter' => '/kernel/cache/ICacheAdapter.php',
        'filestorage'   =>  '/kernel/cache/FileStorage.php',
        'messanger' =>  '/lib/Messanger.php',
        'moduledelegator'   =>  '/kernel/page/ModuleDelegator.php',
        'description'   =>  '/kernel/Description.php',
        'descriptionlist'   => '/kernel/DescriptionList.php',
        'link'  =>  '/lib/Link.php',
        'linkcreator'   => '/lib/LinkCreator.php',
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