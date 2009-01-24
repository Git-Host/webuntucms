<?php
require_once __WEB_ROOT__ . '/Bobr/AutoLoader.php';

// Nacteme si vyjimky pro kernel
require_once __WEB_ROOT__ . '/Bobr/exception.php';

class Bobr_BobrLoader extends Bobr_AutoLoader
{
	/** @var BobrLoader */
	public static $instance;

	/** @var string  base file path */
	public $base;

	public $list = array(
        // Kernel
        'bobr_bobr' => '/Bobr/Bobr.php',
        'bobr_colection' => '/Bobr/Colection.php',
        'bobr_bobrloader' => '/Bobr/BobrLoader.php',
        'bobr_autoloader' => '/Bobr/AutoLoader.php',
        'bobr_command' => '/Bobr/Command.php',
        'bobr_commandvalidator' => '/Bobr/CommandValidator.php',
        'bobr_dataobject' => '/Bobr/DataObject.php',
        'bobr_dataobjectinterface' => '/Bobr/DataObjectInterface.php',
        'bobr_description' => '/Bobr/Description.php',
        'bobr_descriptionlist' => '/Bobr/DescriptionList.php',
        'bobr_lang' => '/Bobr/Lang.php',
        'bobr_langlist' => '/Bobr/LangList.php',
        'bobr_process' => '/Bobr/Process.php',
        'bobr_session' => '/Bobr/Session.php',
        'bobr_sessionvalidator' => '/Bobr/SessionValidator.php',
        'bobr_webinstancevalidator' => '/Bobr/WebInstanceValidator.php',
        // Cache
        'bobr_cache_cache' => '/Bobr/Cache/Cache.php',
        'bobr_cache_cacheadapterinterface' => '/Bobr/Cache/CacheAdapterInterface.php',
        'bobr_cache_filestorage' => '/Bobr/Cache/FileStorage.php',
        // Config
        'config' => '/Bobr/Config/localconfig.php',
        'abstractconfig' => '/Bobr/Config/AbstractConfig.php',
        'defaultconfig' => '/Bobr/Config/defaultconfig.php',
        'indexconfig' => '/Bobr/Config/indexconfig.php',
        'adminconfig' => '/Bobr/Config/adminconfig.php',
        // Page
        'bobr_page_template' => '/Bobr/Page/Template.php',
        'bobr_page_page' => '/Bobr/Page/Page.php',
        'bobr_page_pageid' => '/Bobr/Page/PageId.php',
        'bobr_page_container' => '/Bobr/Page/Container.php',
        'bobr_page_containercolection' => '/Bobr/Page/ContainerColection.php',
        'bobr_page_block' => '/Bobr/Page/Block.php',
        'bobr_page_moduledelegator' => '/Bobr/Page/ModuleDelegator.php',
        'document' => '/Bobr/Page/Document.php',
        // Request
        'bobr_request_httprequest' => '/Bobr/Request/HttpRequest.php',
        'bobr_request_httpproperty' => '/Bobr/Request/HttpProperty.php',
        'bobr_request_httppropertyinterface' => '/Bobr/Request/HttpPropertyInterface.php',
        'bobr_request_httpget' => '/Bobr/Request/HttpGet.php',
        'bobr_request_httppost' => '/Bobr/Request/HttpPost.php',
        'bobr_request_httpcookie' => '/Bobr/Request/HttpCookie.php',
        'bobr_request_httpfile' => '/Bobr/Request/HttpFile.php',
        'bobr_request_httpheader' => '/Bobr/Request/HttpHeader.php',
        'bobr_request_route' => '/Bobr/Request/Route.php',
        'bobr_request_dynamicroute' => '/Bobr/Request/DynamicRoute.php',
        // User
        'bobr_user_functionslist' => '/Bobr/User/FunctionsList.php',
        'bobr_user_group' => '/Bobr/User/Group.php',
        'bobr_user_groupslist' => '/Bobr/User/GroupsList.php',
        'bobr_user_module' => '/Bobr/User/Module.php',
        'bobr_user_modulefunction' => '/Bobr/User/ModuleFunction.php',
        'bobr_user_modulelist' => '/Bobr/User/ModuleList.php',
        'bobr_user_user' => '/Bobr/User/User.php',
        'bobr_user_userlogin' => '/Bobr/User/UserLogin.php',
        'bobr_user_uservalidator' => '/Bobr/User/UserValidator.php',
        'bobr_user_webinstance' => '/Bobr/User/WebInstance.php',
        'bobr_user_webinstancelist' => '/Bobr/User/WebInstanceList.php',
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