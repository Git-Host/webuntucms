
<?php
/**
 * HTML Singleton
 * Trida slouzi k vypisovani HTML
 */
class PageHtml extends Object
{
	private static $instance = FALSE;

	// pokud je potreba tak se nacte BobrConfig
	private $config = NULL;

	// do teto promene se uklada prubezne vystup
	private $outputHTML;

	// ukladame sem nadpis stranky
	private $webTitle = array();

	private $metaKeywords;
	private $metaDescription;
	private $webAuthor;
	private $webmaster;
	private $copyright;
	private $favicon;

	private $CSS	= array();
	private $feed	= array();
	private $script;

	/**
	 * Web Instance velkymi pismeny
	 *
	 * @var string
	 */
	private  $upperWebInstance;


	/**
	 * Vrati singleton instanci
	 * @return Object
	 */
	public static function getInstance() {
		if( self::$instance === FALSE ) {
			self::$instance = new PageHtml();
		}
		return self::$instance;
	}

	/**
	 * Vrati vygenerovanou stranku jako parametr.
	 *
	 * @param void
	 * @return string
	 */
	public function getPage()
	{
		$page = $this->getHead();
		$page .= $this->getOutput();
		$page .= $this->getFooter();

		return $page;
	}

	/**
	 * Prida do "globalu" string
	 *  take prida na konec stringu znacku konce radku
	 * @param $string Object
	 * @return mixed $this
	 */
	public function addOutput( $string )
	{
		$this->outputHTML .= $string . "\r\n";
		return $this;
	}

	/**
	 * Prida string do pole titulku, ktere pak musi zpracovat
	 * metoda getWebTitle
	 * @return
	 * @param $title String
	 */
	public function addWebTitle ( $title )
	{
		$this->webTitle[] = $title;
		return $this;
	}

	/**
	 * Prida cestu k css
	 * zpracovava to metoda getCSS()
	 * @return
	 * @param $source string
	 */
	public function addCSS ( $source )
	{
		$this->CSS[] = $source;
		return $this;
	}

	/**
	 * Prida cestu a popis feedu
	 * zpracovava metoda getFeed()
	 * @return
	 * @param $source string
	 * @param $description string
	 */
	public function addFeed ( $source, $description )
	{
		$this->feed[] = array( 'source' => $source, 'description' => $description );
		return $this;
	}

	/**
	 * Prida string mezi ostatni
	 * metoda getScript to zpracuje a da to v html do hlavicky
	 * @return
	 * @param $script Object
	 */
	public function addScript( $script )
	{
		$this->script .= $script;
		return $this;
	}

	public function setMetaKeywords( $keywords )
	{
		$this->metaKeywords = $kewords;
		return $this;
	}

	public function setMetaDescription( $description )
	{
		$this->metaDescription = $description;
		return $this;
	}

	public function setWebAuthor ( $author )
	{
		$this->webAuthor = $author;
		return $this;
	}

	public function setWebmaster( $webmaster )
	{
		$this->webmaster = $webmaster;
		return $this;
	}

	public function setCopyright( $copyright )
	{
		$this->copyright = $copyright;
		return $this;
	}

	public function setFavicon( $favicon )
	{
		$this->favicon = $favicon;
		return $this;
	}

	/**
	 * Nastavi pro vnitrni ucely web instanci a zvetsi string.
	 * Pouziva se pak pro volani hodnot v configu
	 *
	 * @param string
	 * @return void
	 */
	public function setWebInstance( $webInstance )
	{
		$this->upperWebInstance = strtoupper( $webInstance );
		return $this;
	}


	/**
	 * Vrati vlastnost outputHTML, do ktere se uklada veskery obsah
	 *
	 * @return string
	 */
	public function getOutput()
	{
		return $this->outputHTML;
	}

	/**
	 * Vrati html hlavicku a postara se o naplneni prislusnymi daty
	 * @return string
	 */
	public function getHead()
	{
		/*
		 * vytvorime si instanci configu a ulozime ji do objektu protoze
		 * vetsina get metod config pouzije tak at ho nemusej instancovat zvlast
		 */
		$this->config = BobrConf::getInstance();
		$output = "<?xml version=\"1.0\" encoding=\"" . BobrConf::WEB_ENCODING . "\"?>\n";
		$output .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
<html>
	<head>
		<title>" . $this->getWebTitle() . "</title>
		<meta http-equiv=\"content-type\" content=\"text/html; charset=" . BobrConf::WEB_ENCODING . "\" />
		<meta name=\"keywords\" content=\"" . $this->getMetaKeywords() . "\" />
		<meta name=\"description\" content=\"" . $this->MetaDescription() . "\" />
		<meta name=\"author\" content=\"" . $this->getWebAuthor() . "\" />
		<meta name=\"webmaster\" content=\"" . $this->getWebmaster() . "\" />
		<meta name=\"copyright\" content=\"" . $this->getCopyright() . "\" />
		<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" . $this->getFavicon() . "\" />\n";
		$output .= $this->getCSS();
		$output .= $this->getFeed();
		$output .= $this->getScript();
		$output .= "\n\t</head>\n\t<body>\r\n";
		return $output;
	}
	private function getFooter()
	{
		return "\n\t</body>\n</html>";
	}


	/**
	 * Vygeneruje titulek stranky a vrati ho jako navratovou hodnotu
	 * @return $webTitle string
	 */
	public function getWebTitle ()
	{
		$config = BobrConf::getInstance();
		if ( empty( $this->webTitle ) ){
			$webTitle = $config[$this->upperWebInstance. '_TITLE'];
		}else {

			$webTitle = '';
			if( TRUE === $config[$this->upperWebInstance. '_TITLE_REVERT'] ){
				$title = array_reverse( $this->webTitle );
			}else {
				$title = $this->webTitle;
			}

			$separator = $config[$this->upperWebInstance. '_TITLE_SEPARATOR'];

			foreach ($title as $value){
				!empty( $value ) ? $webTitle .= $value . ' ' . $separator . ' ' : NULL;
			}
			$webTitle = iconv_substr( $webTitle, 0,  iconv_strlen( $webTitle ) - 2) . ' ' . $separator . ' ' . $config['WEB_TITLE'];
		}

		return $webTitle;

	}

	public function getMetaKeywords()
	{
		empty( $this->metaKeywords )
			? $keyWords = $this->config[$this->upperWebInstance. '_META_KEYWORDS']
			: $keyWords = $this->metaKeywords;
		return $keyWords;
	}
	public function MetaDescription()
	{
		empty( $this->metaDescrition )
			? $description = $this->config[$this->upperWebInstance. '_META_DESCRIPTION']
			: $description = $this->metaDescription;
		return $description;
	}
	public function getWebAuthor()
	{
		empty( $this->webAuthor )
			? $author = $this->config[$this->upperWebInstance. '_AUTHOR']
			: $author = $this->webAuthor;
		return $author;
	}
	public function getWebmaster()
	{
		empty( $this->webmaster )
			? $webmaster = $this->config[$this->upperWebInstance. '_WEBMASTER']
			: $webmaster = $this->webmaster;
		return $webmaster;
	}
	public function getCopyright()
	{
		empty( $this->copyright )
			? $copyright = $this->config[$this->upperWebInstance. '_COPYRIGHT']
			: $copyright = $this->copyright;
		return $copyright;
	}
	public function getFavicon()
	{
		empty( $this->favicon )
			? $favicon = $this->config[$this->upperWebInstance. '_FAVICON']
			: $favicon = $this->favicon;
		return $favicon;
	}
	public function getCSS()
	{
		if ( empty( $this->CSS) ){
			return FALSE;
		}else {
			$output = '';
			foreach ( $this->CSS as $source ){
				$output .= "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $source . "\" />\n";
			}
			return $output;
		}
	}
	public function getFeed()
	{
		if ( empty( $this->feed) ){
			return FALSE;
		}else {
			$output = '';
			foreach ( $this->feed as $link){
				$output .= "\t\t<link rel=\"alternate\" type=\"application/rss+xml\" title=\"" . $link['description'] . "\" href=\"" . $link['source'] . "\" />\n";
			}
			return $output;
		}
	}
	public function getScript()
	{
		return $this->script;
	}

	public function delOutput()
	{
		$this->outputHTML = '';
	}

	public static function tag($tag, $text, $other = NULL )
	{
		$output	= '';
		$tag = mb_strtolower( $tag );

		$output.="<$tag";
		if( is_array( $other ) )
		{
	    	foreach ( $other as $attr => $v ) {
	    		$output .= " " . mb_strtolower( $attr ) ." = \"" . mb_strtolower( $v ) . "\" ";
	    	}
	    	$output .= ">$text</$tag>\n";

		} elseif( is_string( $other ) ) {
			$output .= ' ' . $other .">$text</$tag>\n";

		} else {
			$output .= ">$text</$tag>\n";
		}

		return $output;
  	}

  	public static function tagOpen( $tag, $attributes = NULL )
	{
		$output	= '';
		$output .= "<$tag";
		$output .= self::checkTagAttributes( $attributes );
		$output .= ">\n";

		return $output;
	}

	public static function tagClose( $tag ) {
		return is_string( $tag ) ? "</$tag>\n" : FALSE;
	}


	public static function tagSingle( $tag , $attributes = NULL ) {
    	$output = '';
		$tag = mb_strtolower( $tag );
		$inTag = mb_strtolower( $inTag );

		$output	.= "<$tag";
		$output .= self::checkTagAttributes( $attributes );
		$output .= "\" />\n";
		return $output;
    }

    public static function tagPair( $tag , $inTag, $attributes = NULL ) {
		$output = '';
		$tag = mb_strtolower( $tag );
		$inTag = mb_strtolower( $inTag );

		$output	.= "<$tag";
		$output .= self::checkTagAttributes( $attributes );
		$output .= ">$inTag</$tag>\n";
		return $output;
	}

    public static function checkTagAttributes( $attributes ) {
    	$output .= '';
    	if( is_array( $attributes ) ) {
			foreach ( $attributes as $attr => $v ) {
				$output .= ' '.$attr . '= "' . $v . '"';
			}
		} elseif( is_string( $attributes ) ) {
			$output .= ' '.$attributes;
		}
		return $output;
    }



	public static function aHref( $url, $text, $attributes = NULL )
	{
		$url	= mb_strtolower( $url );

		return ( NULL === $attributes )
				? self::tagPair( 'a' , $text , array('href' => $url, 'title'=>$text ))
				: self::tagPair( 'a' , $text , array('href' => $url, $attributes ));
	}

}