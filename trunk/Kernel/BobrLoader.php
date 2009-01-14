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
        'kernel_bobr' => '/Kernel/Bobr.php',
        'kernel_colection' => '/Kernel/Colection.php',
        'kernel_bobrloader' => '/Kernel/BobrLoader.php',
        'kernel_autoloader' => '/Kernel/AutoLoader.php',
        'kernel_command' => '/Kernel/Command.php',
        'kernel_commandvalidator' => '/Kernel/CommandValidator.php',
        'kernel_dataobject' => '/Kernel/DataObject.php',
        'kernel_dataobjectinterface' => '/Kernel/DataObjectInterface.php',
        'kernel_description' => '/Kernel/Description.php',
        'kernel_descriptionlist' => '/Kernel/DescriptionList.php',
        'kernel_lang' => '/Kernel/Lang.php',
        'kernel_langlist' => '/Kernel/LangList.php',
        'kernel_process' => '/Kernel/Process.php',
        'kernel_session' => '/Kernel/Session.php',
        'kernel_sessionvalidator' => '/Kernel/SessionValidator.php',
        'kernel_webinstancevalidator' => '/Kernel/WebInstanceValidator.php',
        // Cache
        'kernel_cache_cache' => '/Kernel/Cache/Cache.php',
        'kernel_cache_cacheadapterinterface' => '/Kernel/Cache/CacheAdapterInterface.php',
        'kernel_cache_filestorage' => '/Kernel/Cache/FileStorage.php',
        // Config
        'config' => '/Kernel/Config/localconfig.php',
        'abstractconfig' => '/Kernel/Config/AbstractConfig.php',
        'defaultconfig' => '/Kernel/Config/defaultconfig.php',
        'indexconfig' => '/Kernel/Config/indexconfig.php',
        'adminconfig' => '/Kernel/Config/adminconfig.php',
        // Page
        'kernel_page_template' => '/Kernel/Page/Template.php',
        'kernel_page_page' => '/Kernel/Page/Page.php',
        'kernel_page_pageid' => '/Kernel/Page/PageId.php',
        'kernel_page_container' => '/Kernel/Page/Container.php',
        'kernel_page_containercolection' => '/Kernel/Page/ContainerColection.php',
        'kernel_page_block' => '/Kernel/Page/Block.php',
        'kernel_page_moduledelegator' => '/Kernel/Page/ModuleDelegator.php',
        'document' => '/Kernel/Page/Document.php',
        // Request
        'kernel_request_httprequest' => '/Kernel/Request/HttpRequest.php',
        'kernel_request_httpproperty' => '/Kernel/Request/HttpProperty.php',
        'kernel_request_httppropertyinterface' => '/Kernel/Request/HttpPropertyInterface.php',
        'kernel_request_httpget' => '/Kernel/Request/HttpGet.php',
        'kernel_request_httppost' => '/Kernel/Request/HttpPost.php',
        'kernel_request_httpcookie' => '/Kernel/Request/HttpCookie.php',
        'kernel_request_httpfile' => '/Kernel/Request/HttpFile.php',
        'kernel_request_httpheader' => '/Kernel/Request/HttpHeader.php',
        'kernel_request_route' => '/Kernel/Request/Route.php',
        'kernel_request_dynamicroute' => '/Kernel/Request/DynamicRoute.php',
        // User
        'kernel_user_functionslist' => '/Kernel/User/FunctionsList.php',
        'kernel_user_group' => '/Kernel/User/Group.php',
        'kernel_user_groupslist' => '/Kernel/User/GroupsList.php',
        'kernel_user_module' => '/Kernel/User/Module.php',
        'kernel_user_modulefunction' => '/Kernel/User/ModuleFunction.php',
        'kernel_user_modulelist' => '/Kernel/User/ModuleList.php',
        'kernel_user_user' => '/Kernel/user/User.php',
        'kernel_user_userlogin' => '/Kernel/User/UserLogin.php',
        'kernel_user_uservalidator' => '/Kernel/User/UserValidator.php',
        'kernel_user_webinstance' => '/Kernel/User/WebInstance.php',
        'kernel_user_webinstancelist' => '/Kernel/User/WebInstanceList.php',
        // Lib
        'lib_abstractmodule' => '/Lib/AbstractModule.php',
        'lib_imodule' => '/Lib/IModule.php',
        'lib_link' => '/Lib/Link.php',
        'lib_linkcreator' => '/Lib/LinkCreator.php',
        'lib_messanger' => '/Lib/Messanger.php',
        'lib_tools' => '/Lib/Tools.php',

        // Dibi
        'dibi' => '/Lib/dibi/dibi.php',
        // Nette::Forms
		'button' => '/Lib/Nette/Forms/Controls/Button.php',
		'checkbox' => '/Lib/Nette/Forms/Controls/Checkbox.php',
		'component' => '/Lib/Nette/Component.php',
		'componentcontainer' => '/Lib/Nette/ComponentContainer.php',
		'conventionalrenderer' => '/Lib/Nette/Forms/Renderers/ConventionalRenderer.php',
		'debug' => '/Lib/Nette/Debug.php',
		'directorynotfoundexception' => '/Lib/Nette/exceptions.php',
		'environment' => '/Lib/Nette/Environment.php',
		'fatalerrorexception' => '/Lib/Nette/exceptions.php',
		'filenotfoundexception' => '/Lib/Nette/exceptions.php',
        'fileupload' => '/Lib/Nette/Forms/Controls/FileUpload.php',
		'form' => '/Lib/Nette/Forms/Form.php',
		'formcontainer' => '/Lib/Nette/Forms/FormContainer.php',
		'formcontrol' => '/Lib/Nette/Forms/Controls/FormControl.php',
		'formgroup' => '/Lib/Nette/Forms/FormGroup.php',
		'forwardingexception' => '/Lib/Nette/Application/ForwardingException.php',
		'framework' => '/Lib/Nette/Framework.php',
		'hiddenfield' => '/Lib/Nette/Forms/Controls/HiddenField.php',
		'html' => '/Lib/Nette/Web/Html.php',
		'icomponent' => '/Lib/Nette/IComponent.php',
		'icomponentcontainer' => '/Lib/Nette/IComponentContainer.php',
		'idebuggable' => '/Lib/Nette/IDebuggable.php',
		'iformcontrol' => '/Lib/Nette/Forms/IFormControl.php',
        'iformrenderer' => '/Forms/IFormRenderer.php',
		'imagebutton' => '/Lib/Nette/Forms/Controls/ImageButton.php',
		'invalidlinkexception' => '/Lib/Nette/Application/InvalidLinkException.php',
		'invalidpresenterexception' => '/Lib/Nette/Application/InvalidPresenterException.php',
		'invalidstateexception' => '/Lib/Nette/exceptions.php',
        'instantclientscript' => '/Lib/Nette/Forms/Renderers/InstantClientScript.php',
        'instancefilteriterator' => '/Lib/Nette/InstanceFilterIterator.php',
		'ioexception' => '/Lib/Nette/exceptions.php',
		'ipresenter' => '/Lib/Nette/Application/IPresenter.php',
		'ipresenterloader' => '/Lib/Nette/Application/IPresenterLoader.php',
		'isubmittercontrol' => '/Lib/Nette/Forms/ISubmitterControl.php',
		'itemplate' => '/Lib/Nette/Templates/ITemplate.php',
		'memberaccessexception' => '/Lib/Nette/exceptions.php',
		'multiselectbox' => '/Lib/Nette/Forms/Controls/MultiSelectBox.php',
		'notimplementedexception' => '/Lib/Nette/exceptions.php',
		'notsupportedexception' => '/Lib/Nette/exceptions.php',
		'object' => '/Lib/Nette/Object.php',
		'radiolist' => '/Lib/Nette/Forms/Controls/RadioList.php',
		'recursivecomponentiterator' => '/Lib/Nette/ComponentContainer.php',
		'recursivehtmliterator' => '/Lib/Nette/Web/Html.php',
		'repeatercontrol' => '/Lib/Nette/Forms/Controls/RepeaterControl.php',
		'rule' => '/Lib/Nette/Forms/Rule.php',
		'rules' => '/Lib/Nette/Forms/Rules.php',
		'selectbox' => '/Lib/Nette/Forms/Controls/SelectBox.php',
		'servicelocator' => '/Lib/Nette/ServiceLocator.php',
		'set' => '/Lib/Nette/Collections/Set.php',
		'submitbutton' => '/Lib/Nette/Forms/Controls/SubmitButton.php',
		'textarea' => '/Lib/Nette/Forms/Controls/TextArea.php',
		'textbase' => '/Lib/Nette/Forms/Controls/TextBase.php',
		'textinput' => '/Lib/Nette/Forms/Controls/TextInput.php',
        'userclientscript' => '/Lib/Nette/Forms/Renderers/UserClientScript.php',
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