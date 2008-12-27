<?php
require_once __WEB_ROOT__ . '/kernel/AutoLoader.php';

// Nacteme si vyjimky pro kernel
require_once __WEB_ROOT__ . '/kernel/exception.php';

class BobrLoader extends AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
        'bobr' => '/kernel/Bobr.php',
        'bobrloader' => '/kernel/BobrLoader.php',
        'autoloader' => '/kernel/AutoLoader.php',
        'command' => '/kernel/Command.php',
        'config' => '/kernel/config/localconfig.php',
        'abstractconfig' => '/kernel/config/AbstractConfig.php',
        'defaultconfig' => '/kernel/config/defaultconfig.php',
        'indexconfig' => '/kernel/config/indexconfig.php',
        'adminconfig' => '/kernel/config/adminconfig.php',
        'session' => '/kernel/Session.php',
        'commandvalidator' => '/kernel/CommandValidator.php',
        'sessionvalidator' => '/kernel/SessionValidator.php',
        'webinstancevalidator' => '/kernel/WebInstanceValidator.php',
        'user' => '/kernel/user/User.php',
        'userlogin' => '/kernel/user/UserLogin.php',
        'uservalidator' => '/kernel/user/UserValidator.php',
        'groupslist' => '/kernel/user/GroupsList.php',
        'group' => '/kernel/user/Group.php',
        'modulelist' => '/kernel/user/ModuleList.php',
        'module' => '/kernel/user/Module.php',
        'functionslist' => '/kernel/user/FunctionsList.php',
        'modulefunction' => '/kernel/user/ModuleFunction.php',
        'webinstancelist' => '/kernel/user/WebInstanceList.php',
        'webinstance' => '/kernel/user/WebInstance.php',
        'template' => '/kernel/page/Template.php',
        'pagebuilder' => '/kernel/page/PageBuilder.php',
        'page' => '/kernel/page/Page.php',
        'pageid' => '/kernel/page/PageId.php',
        'csslist' => '/kernel/page/CssList.php',
        'css' => '/kernel/page/Css.php',
        'containerlist' => '/kernel/page/ContainerList.php',
        'container' => '/kernel/page/Container.php',
        'containercolection' => '/kernel/page/ContainerColection.php',
        'colection' => '/kernel/Colection.php',
        'blockslist' => '/kernel/page/BlocksList.php',
        'block' => '/kernel/page/Block.php',
        'javascriptlist' => '/kernel/page/JavascriptList.php',
        'javascript' => '/kernel/page/Javascript.php',
        'metataglist' => '/kernel/page/MetatagList.php',
        'metatag' => '/kernel/page/Metatag.php',
        'document' => '/kernel/page/Document.php',
        'httprequest' => '/kernel/request/HttpRequest.php',
        'httpproperty' => '/kernel/request/HttpProperty.php',
        'ihttpproperty' => '/kernel/request/IHttpProperty.php',
        'httpget' => '/kernel/request/HttpGet.php',
        'httppost' => '/kernel/request/HttpPost.php',
        'httpcookie' => '/kernel/request/HttpCookie.php',
        'httpfile' => '/kernel/request/HttpFile.php',
        'httpheader' => '/kernel/request/HttpHeader.php',
        'file' => '/kernel/request/File.php',
        'process' => '/kernel/Process.php',
        'route' => '/kernel/request/Route.php',
        'dynamicroute' => '/kernel/request/DynamicRoute.php',
        'lang' => '/kernel/Lang.php',
        'langlist' => '/kernel/LangList.php',
        'tools' => '/lib/Tools.php',
        'dibi' => '/lib/dibi/dibi.php',
        'imodule' => '/lib/IModule.php',
        'abstractmodule' => '/lib/AbstractModule.php',
        'sample' => '/sample.php',
        'cache' => '/kernel/cache/Cache.php',
        'dataobject' => '/kernel/cache/DataObject.php',
        'idataobject' => '/kernel/cache/IDataObject.php',
        'icacheadapter' => '/kernel/cache/ICacheAdapter.php',
        'filestorage' => '/kernel/cache/FileStorage.php',
        'messanger' => '/lib/Messanger.php',
        'moduledelegator' => '/kernel/page/ModuleDelegator.php',
        'description' => '/kernel/Description.php',
        'descriptionlist' => '/kernel/DescriptionList.php',
        'link' => '/lib/Link.php',
        'linkcreator' => '/lib/LinkCreator.php',


		'button' => '/lib/Nette/Forms/Controls/Button.php',
		'checkbox' => '/lib/Nette/Forms/Controls/Checkbox.php',
		'component' => '/lib/Nette/Component.php',
		'componentcontainer' => '/lib/Nette/ComponentContainer.php',
		'conventionalrenderer' => '/lib/Nette/Forms/Renderers/ConventionalRenderer.php',
		'debug' => '/lib/Nette/Debug.php',
		'directorynotfoundexception' => '/lib/Nette/exceptions.php',
		'environment' => '/lib/Nette/Environment.php',
		'fatalerrorexception' => '/lib/Nette/exceptions.php',
		'filenotfoundexception' => '/lib/Nette/exceptions.php',
        'fileupload' => '/lib/Nette/Forms/Controls/FileUpload.php',
		'form' => '/lib/Nette/Forms/Form.php',
		'formcontainer' => '/lib/Nette/Forms/FormContainer.php',
		'formcontrol' => '/lib/Nette/Forms/Controls/FormControl.php',
		'formgroup' => '/lib/Nette/Forms/FormGroup.php',
		'forwardingexception' => '/lib/Nette/Application/ForwardingException.php',
		'framework' => '/lib/Nette/Framework.php',
		'hiddenfield' => '/lib/Nette/Forms/Controls/HiddenField.php',
		'html' => '/lib/Nette/Web/Html.php',
		'icomponent' => '/lib/Nette/IComponent.php',
		'icomponentcontainer' => '/lib/Nette/IComponentContainer.php',
		'idebuggable' => '/lib/Nette/IDebuggable.php',
		'iformcontrol' => '/lib/Nette/Forms/IFormControl.php',
        'iformrenderer' => '/Forms/IFormRenderer.php',
		'imagebutton' => '/lib/Nette/Forms/Controls/ImageButton.php',
		'invalidlinkexception' => '/lib/Nette/Application/InvalidLinkException.php',
		'invalidpresenterexception' => '/lib/Nette/Application/InvalidPresenterException.php',
		'invalidstateexception' => '/lib/Nette/exceptions.php',
        'instantclientscript' => '/lib/Nette/Forms/Renderers/InstantClientScript.php',
        'instancefilteriterator' => '/lib/Nette/InstanceFilterIterator.php',
		'ioexception' => '/lib/Nette/exceptions.php',
		'ipresenter' => '/lib/Nette/Application/IPresenter.php',
		'ipresenterloader' => '/lib/Nette/Application/IPresenterLoader.php',
		'isubmittercontrol' => '/lib/Nette/Forms/ISubmitterControl.php',
		'itemplate' => '/lib/Nette/Templates/ITemplate.php',
		'memberaccessexception' => '/lib/Nette/exceptions.php',
		'multiselectbox' => '/lib/Nette/Forms/Controls/MultiSelectBox.php',
		'notimplementedexception' => '/lib/Nette/exceptions.php',
		'notsupportedexception' => '/lib/Nette/exceptions.php',
		'object' => '/lib/Nette/Object.php',
		'radiolist' => '/lib/Nette/Forms/Controls/RadioList.php',
		'recursivecomponentiterator' => '/lib/Nette/ComponentContainer.php',
		'recursivehtmliterator' => '/lib/Nette/Web/Html.php',
		'repeatercontrol' => '/lib/Nette/Forms/Controls/RepeaterControl.php',
		'rule' => '/lib/Nette/Forms/Rule.php',
		'rules' => '/lib/Nette/Forms/Rules.php',
		'selectbox' => '/lib/Nette/Forms/Controls/SelectBox.php',
		'servicelocator' => '/lib/Nette/ServiceLocator.php',
		'set' => '/lib/Nette/Collections/Set.php',
		'submitbutton' => '/lib/Nette/Forms/Controls/SubmitButton.php',
		'textarea' => '/lib/Nette/Forms/Controls/TextArea.php',
		'textbase' => '/lib/Nette/Forms/Controls/TextBase.php',
		'textinput' => '/lib/Nette/Forms/Controls/TextInput.php',
        'userclientscript' => '/lib/Nette/Forms/Renderers/UserClientScript.php',
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