<?php
require_once __WEB_ROOT__ . '/kernel/AutoLoader.php';

// Nacteme si vyjimky pro kernel
require_once __WEB_ROOT__ . '/kernel/exception.php';

class Kernel_BobrLoader extends Kernel_AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
        // Kernel
        'kernel_bobr' => '/kernel/Bobr.php',
        'kernel_colection' => '/kernel/Colection.php',
        'kernel_bobrloader' => '/kernel/BobrLoader.php',
        'kernel_autoloader' => '/kernel/AutoLoader.php',
        'kernel_command' => '/kernel/Command.php',
        'kernel_commandvalidator' => '/kernel/CommandValidator.php',
        'kernel_dataobject' => '/kernel/DataObject.php',
        'kernel_dataobjectinterface' => '/kernel/DataObjectInterface.php',
        'kernel_description' => '/kernel/Description.php',
        'kernel_descriptionlist' => '/kernel/DescriptionList.php',
        'kernel_lang' => '/kernel/Lang.php',
        'kernel_langlist' => '/kernel/LangList.php',
        'kernel_process' => '/kernel/Process.php',
        'kernel_session' => '/kernel/Session.php',
        'kernel_sessionvalidator' => '/kernel/SessionValidator.php',
        'kernel_webinstancevalidator' => '/kernel/WebInstanceValidator.php',
        // Cache
        'kernel_cache_cache' => '/kernel/cache/Cache.php',
        'kernel_cache_cacheadapterinterface' => '/kernel/cache/CacheAdapterInterface.php',
        'kernel_cache_filestorage' => '/kernel/cache/FileStorage.php',
        // Config
        'config' => '/kernel/config/localconfig.php',
        'abstractconfig' => '/kernel/config/AbstractConfig.php',
        'defaultconfig' => '/kernel/config/defaultconfig.php',
        'indexconfig' => '/kernel/config/indexconfig.php',
        'adminconfig' => '/kernel/config/adminconfig.php',
        // Page
        'kernel_page_template' => '/kernel/page/Template.php',
        'kernel_page_page' => '/kernel/page/Page.php',
        'kernel_page_pageid' => '/kernel/page/PageId.php',
        'kernel_page_container' => '/kernel/page/Container.php',
        'kernel_page_containercolection' => '/kernel/page/ContainerColection.php',
        'kernel_page_block' => '/kernel/page/Block.php',
        'kernel_page_moduledelegator' => '/kernel/page/ModuleDelegator.php',
        'document' => '/kernel/page/Document.php',
        // Request
        'kernel_request_httprequest' => '/kernel/request/HttpRequest.php',
        'kernel_request_httpproperty' => '/kernel/request/HttpProperty.php',
        'kernel_request_httppropertyinterface' => '/kernel/request/HttpPropertyInterface.php',
        'kernel_request_httpget' => '/kernel/request/HttpGet.php',
        'kernel_request_httppost' => '/kernel/request/HttpPost.php',
        'kernel_request_httpcookie' => '/kernel/request/HttpCookie.php',
        'kernel_request_httpfile' => '/kernel/request/HttpFile.php',
        'kernel_request_httpheader' => '/kernel/request/HttpHeader.php',
        'kernel_request_route' => '/kernel/request/Route.php',
        'kernel_request_dynamicroute' => '/kernel/request/DynamicRoute.php',
        // User
        'kernel_user_functionslist' => '/kernel/user/FunctionsList.php',
        'kernel_user_group' => '/kernel/user/Group.php',
        'kernel_user_groupslist' => '/kernel/user/GroupsList.php',
        'kernel_user_module' => '/kernel/user/Module.php',
        'kernel_user_modulefunction' => '/kernel/user/ModuleFunction.php',
        'kernel_user_modulelist' => '/kernel/user/ModuleList.php',
        'kernel_user_user' => '/kernel/user/User.php',
        'kernel_user_userlogin' => '/kernel/user/UserLogin.php',
        'kernel_user_uservalidator' => '/kernel/user/UserValidator.php',
        'kernel_user_webinstance' => '/kernel/user/WebInstance.php',
        'kernel_user_webinstancelist' => '/kernel/user/WebInstanceList.php',
        // Lib
        'lib_abstractmodule' => '/lib/AbstractModule.php',
        'lib_imodule' => '/lib/IModule.php',
        'lib_link' => '/lib/Link.php',
        'lib_linkcreator' => '/lib/LinkCreator.php',
        'lib_messanger' => '/lib/Messanger.php',
        'lib_tools' => '/lib/Tools.php',

        // Dibi
        'dibi' => '/lib/dibi/dibi.php',
        // Nette::Forms
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