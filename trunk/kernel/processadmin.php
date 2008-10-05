<?php
final class ProcessAdmin extends ProcessWeb
{
				
	protected	$groupFunctionsList,
				$administrationCategoryList,
				$hashUrl;
	
	protected function setRoot(){
		$this->root = $this->config['ADMIN_ROOT'];
	}
	
 	/**
	 * Osetri get zjisti zda-li se jedna o dynamickou url nebo statickou
	 * a podle toho rodeli volani dalsich method
	 *
	 */
	protected function qPage()
	{
		$this->groupFunctionsList = Module::getSingleton()->getGroupFunctionsList();
		
		if( FALSE === $this->isAdministrationCategory( $this->GET[ $this->getVariable ] ) ){
			Message::addError('Volany modyl neexistuje.');
			$this->redirect( $this->config['ADMIN_ROOT'] );
		}
		
			/*
			$urlHash = md5( $this->GET[ $this->getVariable ] );
			if( array_key_exists( $urlHash, $this->groupFunctionsList ) 
				&&  TRUE == $this->groupFunctionsList[ $urlHash ]['administration'] 
			){
				echo 'picunda';
			}
			*/
		
		
	}
	
	/**
	 * Zjisti zda-li se jenda o kategorii administrace
	 * pokud ano vytvori zaroven command.
	 * 
	 * @param $url string - aktualni uri
	 * @return bool
	 */
	private function isAdministrationCategory( $url )
	{
		if( empty( $url ) ){
			$this->defaultPage();
			return TRUE;
		}else{
			$this->administrationCategoryList = Administration::getSingleton()->getAdministrationCategoryList();
			$category = explode( '/', $url );
			if ( array_key_exists( $category[0], $this->administrationCategoryList ) ){
				// odstranime z url kategorii administrace a zbytek zahasujem pro hledani v poli
				$this->hashUrl = md5( trim( str_replace( $category[0] . '/', '', $this->GET[ $this->getVariable ]) ) );
				// sestavime si page
				$this->pageId = $this->administrationCategoryList[ $category[0] ]['pageid_id'];
				//$this->lang = $this->langList[ $this->config['ADMIN_LANG'] ]['symbol'];
				$this->setAdminLang();
				$this->getPage( $this->pageId );
				array_shift( $category );
				// vytvorime command
				$this->command = $category;
				return TRUE;
			}else {
				$this->defaultPage();
				return FALSE;
			}
		}
	}
	
	/**
	 * Vytvori defaultni page
	 * @param  void
	 * @return bool
	 */
	protected function defaultPage()
	{
		try {
			$this->pageId	= $this->config['ADMIN_PAGEID_DEFAULT'];
			//$this->lang		= $this->langList[ $this->config['ADMIN_LANG'] ];
			$this->setAdminLang();
			$this->getPage( $this->pageId );
	    	return TRUE;
		}catch (UrlException $exception){
			return FALSE;
		}
	}
	
	private function setAdminLang()
	{
		if( NULL === $this->lang){
			return $this->setDefaultLang();
		}else {
			return $this->lang;
		}
	}
	
	protected function setDefaultLang()
    {
    	$this->symbolLangList = Lang::getSingleton()->symbolLangList;
    	if ( ( TRUE === $this->config['BROWSER_PREFERED_LANG'] ) 
    	&& ( TRUE === array_key_exists( $this->LANG, $this->symbolLangList ) ) 
    	){
    		return $this->lang = $this->LANG;
    	}else{
    		return $this->lang = $this->config['ADMIN_LANG'];
    	}
    }
}