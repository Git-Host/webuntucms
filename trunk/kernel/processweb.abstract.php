<?php
/**
 * URLProces je abstraktni trida dedici funkce
 * ze tridy RequestAbstract pro ovladani requestu.
 *
 * Trida slouzi skoro jako rozhrani pro primou praci s uri,
 * kde se bude generovat page na zaklade requestu
 */
abstract class ProcessWeb extends Request
{

	/**
	 * Pole hodnot, ktere ridi moduly
	 *
	 * @var array
	 */
	protected $command = array();

	/**
	 * Cesta k css
	 *
	 * @var string
	 */
	protected $pageCss;
	/**
	 * Cesta k template
	 *
	 * @var string
	 */
	protected $pageTemplate;
	/**
	 * Obsahuje cisla ( id bloku ) oddelene carkou
	 *
	 * @var string
	 */
	protected $pageBlockIds;
	/**
	 * Cilo pageID
	 *
	 * @var integer
	 */
	protected $pageId;
	/**
	 * Obsahuje vsechny dostupne pageId, jako klice pouziva ID
	 *
	 * @var array
	 */
	protected $pageIdList = array();

	/**
	 * Pole dostupnych jazyku
	 *
	 * @var array
	 */
	protected $langList = array();
	/**
	 * Pole jazyku razenych podle symbolu
	 *
	 * @var array
	 */
	protected $symbolLangList;
	/**
	 * Jazyk ve kterem je stranka
	 *
	 * @var string
	 */
	protected $lang;

	/**
	 * Instance BobrConf (konfiguracni trida)
	 *
	 * @var mixed Object
	 */
	protected $config;

	/**
	 * Aktualni akceptovana promena v GETu.
	 * Zpravidla to byva q
	 *
	 * @var string
	 */
	protected $getVariable;
	/**
	 * Pokud jsou v GETu akceptovane promene obsahuje TRUE a naopak
	 *
	 * @var bool
	 */
	protected $requestVariable;

	/**
	 * Root webu na ktery se presmerovava
	 *
	 * @var string
	 */
	protected $root;

	/**
	 * Seznam modulu a metod na ktere ma dana groupa pristup
	 *
	 * @var array
	 */
	protected $moduleList = array();

	/**
	 * Pokud je potreba vytvori se seznam dynamickych modulu
	 *
	 * @var array
	 */
	protected $dynamicUriList = array();

	/**
	 * Zjistujeme jaky bootstrap spustil process pro pozdejsi pouziti
	 *
	 * @var string
	 */
	protected $webInstance;

	/**
	 * URI se kterou se pracuje.
	 * Hodnota URI by mela byt shodna s $this->GET[ $this->getVariable ],
	 * pokud neni je neco spatne!!
	 *
	 * @var string
	 */
	protected $URI;

	/**
	 * Spusti konstruktor bazove tridy a ridi cely proces.
	 * Jako vstupni parametr ma tridu Session
	 *
	 * @param object $session
	 */
	function __construct( Session $session )
	{
		// Do rodicovskeho konstruktoru predavame session kvuli overeni
		parent::__construct( $session );
		$this->config = BobrConf::getInstance();

		$this->setWebInstance();
		$this->setRoot();

		$this->requestVariable = $this->setVariable();

		// nacteme pole s jazykama
		$this->setLangList();
		if( TRUE === $this->config['LANG_SYMBOL_TO_URI'] ){
			$this->searchLang();
		}

		// zjistime jestli neco je v getu... jinak hodime defaultni page
		if( TRUE === $this->ISAJAX ){
			if( FALSE === $this->isDynamicUri( $this->GET[ $this->getVariable ] ) ){
				throw new AjaxException('Dotaz na stranku neni validni.');
			}else {
				throw new AjaxException('POZOR POZOR neni udelan controller na ajaxove requesty.');
			}
		}elseif( $this->URI ){
			$this->controllGetRequest();
		}else{
			$this->defaultPage();
		}
	}
	/**
	 * Zvaliduje promene v GETu, URI a zavola prislusne metody
	 * 	Validace promenych je v poradi q , ADMIN_GET_VARIABLE, text
	 *
	 * @param void
	 */
	protected function controllGetRequest()
	{
		// pokud nema validni promene pryc s tim
		if( FALSE === $this->requestVariable ){
			//$this->redirect( $this->root );
			Message::addError( 'Byla zadana chybna url' );
			$this->redirect( $this->root , 404 );
		}elseif( TRUE === $this->validateUri( $this->GET[ $this->getVariable ] ) ){
			// Zvalidujem si URL
			// vytvorime jmeno obsluhujici metody
			$methodName = $this->getVariable . 'Page';
			// spustime metodu
			if( FALSE === $this->$methodName() ){
				$this->failedUriMessage();
				$this->redirect( $this->root, 404 );
			}

		}
	}


	/**
	 * Zjisti zda-li se jedna o dynamickou URI pokud ano nastavi celou page a vrati TRUE
	 *
	 * @param string $uri
	 * @return bool
	 */
	protected function isDynamicUri( $uri )
	{
		// pomoci heashoveho klice vyhledavame v poli
		$command = explode( '/', $uri );
		$this->setDynamicUriList();
		if ( isset( $this->dynamicUriList[$command[0]] ) ){
			$this->command =	$command;
			$upperWebInstance = strtoupper($this->webInstance);
			$this->pageId	=	$this->dynamicUriList[$command[0]]['page_id'];
			$this->lang		=	$this->langList[$this->dynamicUriList[$command[0]]['lang_id']]['symbol'];
			$this->getPage( $this->pageId );
			return TRUE;
		}else {
			return FALSE;
		}
	}


	/**
	 * Nastavi potrebne veci pro vypsani stranky na defaultni hodnoty
	 *
	 * @param viod
	 * @return bool
	 */
	protected function defaultPage(){
		try{
			$this->command	= 'default';
			$this->lang		= $this->setDefaultLang();
			$upperWebInstance = strtoupper($this->webInstance);
			$this->getPage( $this->config[$upperWebInstance . '_PAGEID_DEFAULT'] );
			/**
			 * Pokud pri nastavovani defaultni stranky nadojde k zadnemu konfliktu
			 * vratime true.
			 */
			return TRUE;
		}catch (UrlException $exception){
			return FALSE;
		}
	}

	/**
	 * Osetri get zjisti zda-li se jedna o dynamickou url nebo statickou
	 * a podle toho rodeli volani dalsich method
	 *
	 * @param viod
	 * @return true / $this->defaultPage();
	 */
	protected function qPage()
	{
		try{
			// podivame se jestli je url staticka
			if (FALSE === $this->isStaticUri( $this->URI ) ){
				// zjistime jestli volani nejni dynamickeho modulu
				return $this->isDynamicUri( $this->URI ) ;
			}else{
				// volani bylo dynamickeho modulu a zdarilo se vratime TRUE
				return TRUE;
			}
		}catch (UrlException $exception){
			// Pridame Fatalni hlasku
			Message::addFatal( $exception->getMessage() );
			Message::addError('Stranku se nepodarilo nacist.');
			return $this->defaultPage();
		}

	}

	/**
	 * Podiva se do databaze jestli je v ni dana uri
	 *
	 * @param string $uri
	 * @return bool
	 */
	public function isStaticUri( $uri )
	{
		$result =  dibi::query( "SELECT command, pageid_id, lang_id
								FROM " . BobrConf::DB_PREFIX . "aliases
								WHERE hash = '" . md5( $uri ) . "' LIMIT 1");

		$result = $result->fetchAll();
		if( count( $result ) ){
			$result = $result[0];
			$this->command		= explode('/', $result['command'] );
			$this->lang			= $this->setLang( $result['lang_id'] );
			$this->pageId		= $result['pageid_id'];
			$this->getPage( $result['pageid_id'] );
			return TRUE;
		}else {
			return FALSE;
		}
	}

	/**
	 * Nastavi promene pro sestaveni stranky.
	 * Pokud pozadovana PageId neexistuje vrati FALSE a naopak
	 *
	 * @param integer $pageId cislo hledane pageID
	 * @return bool pri zdaru vrati TRUE
	 */
	protected function getPage( $pageId )
	{
		$this->setPageIdList();
		if( array_key_exists( $pageId, $this->pageIdList ) ){
			$this->pageCss		=	$this->pageIdList[ $pageId ]['css'];
			$this->pageTemplate	=	$this->pageIdList[ $pageId ]['template'];
			$this->pageBlockIds	=	$this->pageIdList[ $pageId ]['block_ids'];
			return TRUE;
		}else {
			throw new UrlException('Nepodarilo se nacist PageID ' . $pageId . '. S nejvetsi pravdepodobnosti neexistuje v databazi.');
		}
	}

	/**
	 * Zde se natvrdo kontorluji promene v GETu
	 * Paklize GET neobsahuje zadnou ze zde vyjmenovanych promenych
	 * vrati TRUE.
	 * Kdyd v GETu je promena vrati TRUE
	 *
	 * @param void
	 * @return bool
	 */
	protected function setVariable()
	{
		if( array_key_exists( 'q', $this->GET ) ){
			$this->getVariable		=	'q';
			$this->setURI();
			return TRUE;
		}else {
			return FALSE;
		}
	}
	protected function setURI()
	{
		return $this->URI = $this->GET[ $this->getVariable ];
	}

	/**
	 * Loadne vsechny jazyky z databaze popr vyhodi error
	 * @param void
	 * @return array $this->langList - vrati pole jazyku
	 */
	protected function setLangList()
	{
		return $this->langList = Lang::getInstance()->langList;
	}
	/**
	 * Nastavi jazyk
	 *
	 * @param integer $langId - Id langu
	 * @return string $lang
	 */
	protected function setLang( $langId )
	{
		if( TRUE === $this->config['LANG_SYMBOL_TO_URI'] ){
			if( $this->langList[ $langId ]['symbol'] === $this->lang ){
				$this->root = $this->root . $this->lang;
				return $this->lang;
			}else{
				//Ladenka::kill( $this->URI );
				//$this->redirect( $this->lang . '/' . $this->URI );
			}
		}else {
			return $this->langList[ $langId ]['symbol'];
		}

	}
	/**
	 * Nastavi defaultni jazyk podle configu.
	 * Zjisti se jestli se ma brat defaultni hodnota z browseru, pokud ano a jazyk je podporovan
	 * nastavi se jako default.
	 * Pokud ne vezme se defaultni jazyk podle configu.
	 *
	 * @param void
	 * @return string $lang - symbol jazyka
	 */
	protected function setDefaultLang()
	{
		$this->symbolLangList = Lang::getInstance()->symbolLangList;
		if ( ( TRUE === $this->config['BROWSER_PREFERED_LANG'] )
		&& ( TRUE === array_key_exists( $this->LANG, $this->symbolLangList ) )
		){
			return $this->lang = $this->LANG;
		}else{
			$upperWebInstance = strtoupper($this->webInstance);
			return $this->lang = $this->config[$upperWebInstance . '_LANG'];
		}
	}

	/**
	 * Projde pomoci metody setLangToUri() aktualni URI jestli v ni neni symbol akceptovaneho jazyka.
	 * Pokud nenajde jazyk akceptovany presmeruje na defaultni.
	 *
	 * @param viod
	 * @return string $lang
	 */
	protected function searchLang()
	{
		// @todo rbas neni to zde duplicitni?
		$this->setSymbolLangList();
		if( TRUE === empty( $this->URI ) ){
			$this->redirect( $this->root );
		}else {
			//	Ladenka::kill( $this->config['WEB_LANG'] );
			if( FALSE === $this->setLangToUri() ){
				// @todo rbas zajistit aby se do root zapisoval lang budou se z toho generovat linky ktere tuto informaci musi znat
				$this->redirect( $this->root );
			}else {
				return $this->lang;
			}
		}
	}
	/**
	 * Projde aktualni URI ze ktere zjistuje informace o jazyku.
	 * Pokud najde a je akceptovany ( je v poli $symbolLangList ) oreze informace
	 * o jazyku z pole $GET a ze stringu $URI aby se to dale nepouzivalo jako soucasti commandu apod...
	 * Pri nezdaru vraci FALSE.
	 *
	 * @param void
	 * @return FALSE / string $lang
	 */
	private function setLangToUri()
	{
		$lang = explode( '/', $this->URI );
		if( TRUE === array_key_exists( $lang[0], $this->symbolLangList ) ){
			$langSymbol = $lang[0];
			array_shift( $lang );
			if( is_array( $lang ) && FALSE === empty( $lang ) ){
				$this->GET[ $this->getVariable ] = implode( '/', $lang );
				$this->URI = str_replace( $langSymbol . '/', '', $this->URI );
			}else {
				$this->GET[ $this->getVariable ] = '';
				$this->URI = '';
			}
			return $this->lang = $langSymbol;
		}else {
			return FALSE;
		}
	}

	/**
	 * Nastavi pole symbolLangList z objektu Lang
	 *
	 * @param viod
	 * @return array $symbolLangList
	 */
	protected function setSymbolLangList()
	{
		return $this->symbolLangList = Lang::getInstance()->symbolLangList;
	}
	/**
	 * provadi kontrolu zda-li na poslednim miste v uri je lomitko, paklize ano tak ho orizne a presmeruje
	 * na url bez lomitka
	 *
	 * @param url
	 * @return TRUE / presmerovani
	 */
	protected function validateUri($url)
	{
		// zjistime zda-li na poslednim miste v url je a pokud ano tak presmerujem url
		$slash = preg_match ( "@\/$@", $url);
		if ( $slash > 0){
			// oriznem posledni lomitko a presmerujem
			//$this->redirect ( $home_page_separator .substr(  $url, 0, -1 ) );
			$this->redirect ( substr(  $this->REDIRECT_URL, 0, -1 ) );
		}else {
			return TRUE;
		}
	}


	protected function setRoot()
	{
		$upperWebInstance = strtoupper($this->webInstance);
		$this->root = $this->config[$upperWebInstance . '_ROOT'];
	}
	/**
	 * Vrati seznam dynamickych URI
	 *
	 * @param void
	 * @return array $dynamicUriList
	 */
	public function getDynamicUriList()
	{
		if( FALSE === empty( $this->dynamicUriList ) ){
			return $this->dynamicUriList;
		}else {
			return $this->setDynamicUriList();
		}
	}
	/**
	 * vygeneruje pole dynamickych uri
	 * @return
	 */
	protected function setDynamicUriList()
	{
		$webInstanceList = WebInstance::getInstance()->getWebInstanceList();
		if( isset($webInstanceList[ $this->getWebInstance() ] ) ){
			return $this->dynamicUriList = Module::getInstance()->setDynamicModuleList( $webInstanceList[ $this->webInstance ]['id'] );
		}else{
			throw new LogicException('Modul neni urcen pro tuto webinstanci.' . Ladenka::var_dumper($webInstanceList));
		}
	}

	protected function getModuleList()
	{
		$this->moduleList = Module::getInstance()->getGroupFunctionsList();
	}

	/**
	 * V pripade volani dynamickeho modulu se natahne pageId zvlast z databaze
	 *
	 * @return array
	 */
	protected function setPageIdList()
	{
		return $this->pageIdList = PageId::getInstance()->pageIdList;
	}

	/**
	 * Pri spatne zadene URI vygeneruje hlasku do hlaskovace :)
	 *
	 * @param viod
	 */
	protected function failedUriMessage()
	{
		Message::addError( 'Byla zadana chybna url' );
	}

	/**
	 * Nastavi web instanci
	 *
	 * @param void
	 * @return string
	 */
	private function setWebInstance()
	{
		return $this->getWebInstance();
	}

	/**
	 * Pokud process je instance tridy ProcessAdmin
	 * volaji se administracni metody modulu.
	 * Jinak se vola web.
	 *
	 * @param void
	 * @return string nazev webInstance ktera se ma volat
	 */
	public function getWebInstance()
	{
		if ( $this instanceof ProcessAdmin ){
			$this->webInstance = 'admin';
		}else{
			$this->webInstance = 'web';
		}

		return $this->webInstance;
	}


	public function getPageTemplate()
	{
		return $this->pageTemplate;
	}
	public function getPageCss()
	{
		return $this->pageCss;
	}
	public function getPageId()
	{
		return $this->pageId;
	}
	public function getPageBlockIds()
	{
		return $this->pageBlockIds;
	}
	public function getLang()
	{
		return $this->lang;
	}
	public function getRoot()
	{
		return $this->root;
	}
	public function getCommand()
	{
		return $this->command;
	}
}
