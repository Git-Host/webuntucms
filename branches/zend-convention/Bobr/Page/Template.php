<?php
/**
 * Description of Template
 *
 * @author rbas
 */
class Bobr_Page_Template
{
    /**
     * Objekt ContainerColection.
     *
     * @var Bobr_Page_ContainerColection
     */
    private $containerColection = NULL;
    
    /**
     * Promene v sablone.
     *
     * @var array
     */
    public $templateProperties = array();

    /**
     * Vystup.
     *
     * @var string
     */
    private $output = '';

    /**
     * Seznam linku na stylopisy, ktere se maji pridat do hlavicky.
     *
     * @var array
     */
    private $css = array();

    /**
     * Seznam meta tagu.
     *
     * @var array
     */
    private $metaTags = array();

    /**
     * Object
     *
     * @var Bobr_Command
     */
    private $command = NULL;

    /**
     * Vlastni instance.
     *
     * @var Bobr_Page_Template
     */
    private static $instace = NULL;

    /**
     * Urcuje druh stranky ktera se bude renderovat.
     *
     * @var string
     */
    private $documentType = 'page';


    /**
     * Vrati instanci tridy Template.
     *
     * @return Bobr_Page_Template
     */
    public static function getInstance()
    {
        if (NULL === self::$instace) {
            self::$instace = new self;
        }     
        return self::$instace;
    }

    private function __construct()
    {
        $config = new Config;
        // Pridame meta tagy z konfigu
        if ($config->metaTags) {
            $this->metaTags = $config->metaTags;
        }
        // Pokud jsou nejake css v konfigu pridame je.
        if ($config->defaultCss) {
            foreach ((array)$config->defaultCss as $link) {
                $this->addCssLink($link);
            }
        }
        // Pokud je v kofigu nejaky titulek pridame ho do sablony.
        if ($config->webTitle) {
            $this->addVariable('webTitle', $config->webTitle);
        }
    }

    /**
     * Nacte sablonu z cesty fileName.
     * Pokud je parametr retern true vrati vysledek ze sablony jako parametr.
     * Pokud neni pricte vysledek do globalniho vystupu.
     *
     * @param string $fileName
     * @param boolean $return
     * @return string
     */
    public function load($fileName, $return = TRUE)
    {
        if (file_exists($fileName)) {
            ob_start();

            require_once $fileName;
            
            $output = ob_get_contents();
            ob_end_clean();
        } else {
            throw new Bobr_Page_TemplateException('Sablona ' . $fileName . ' neexistuje.');
        }
        if (TRUE === $return) {
            return $output;
        } else {
            $this->output .= $output;
        }
    }

   /**
    * Vypise obsah kontaineru.
    * 
    * @todo toto by se melo vytvaret driv.
    * @param string $container
    * @return string
    */
   public function getContainer($container)
    {
        if (isset($this->containerColection[$container])) {
            foreach ($this->containerColection[$container] as $block) {
                $moduleDelegator = new Bobr_Page_ModuleDelegator;
                $output = "\n<div class=\"block " . $block->getId() . "\">\n";
                $output .= $moduleDelegator->createBlock($block, $this->command);
                $output .= "\n</div>";
            }
            return $output;
        } elseif (empty($this->containerColection)) {
            throw new Bobr_Page_TemplateException('V Template neni nastaven objekt ContainerColection. Nemuze se vypsat stranka.');
        }
    }

    /**
     * Prida do sablony dalsi vlastnost a jeji hodnotu.
     *
     * @param string $name
     * @param mixed $value 
     */
    public static function add($name, $value)
    {
        self::getInstance()->addVariable($name, $value);
    }

    /**
     * Prida do sablony vlastnost a jeji hodnotu.
     *
     * @param string $name
     * @param mixed $value
     * @return Bobr_Page_Template
     */
    public function addVariable($name, $value)
    {
        $this->templateProperties[$name] = $value;
        return $this;
    }


    /**
     * Prida dalsi kaskadovy stylopis do hlavicky.
     *
     * @param string $link
     */
    public static function addCss($link)
    {
        self::getInstance()->addCssLink($link);
    }

    /**
     * Prida link na stylopis do hlavicky
     *
     * @param string $link
     * @return Bobr_Page_Template
     */
    public function addCssLink($link)
    {
        $this->css[] = (string)$link;
        return $this;
    }

    /**
     * Prida meta tag $name s obsahem $content do hlavicky.
     *
     * @param array $tag
     * @return Bobr_Page_Template
     */
    public function addMetaTag(array $tag)
    {
        $this->metaTags[] = $tag;
        return $this;
    }

    /**
     * Vypise linky z vlastnosti css a prida jim html nalezitosti pro nacitani stylopisu.
     *
     * @return string
     */
    private function renderInlineCss()
    {
        $output = '';
        $config = new Config;
        foreach ($this->css as $css) {
            $output .= "\n\t<link href=\"" . $config->share . $css . "\" rel=\"stylesheet\" type=\"text/css\" />";
        }
        return $output;
    }

    private function renderInlineMetaTags()
    {
        $output = '';
        foreach ($this->metaTags as $attrs) {
            $output .= "\n\t" . HTML::el('meta', $attrs);
        }
        return $output;
    }

    /**
     * @todo sestavit poradne hlaicku a ne takto !!!
     * @return string
     */
    private function addHeader()
    {
        $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">';
        $output .=  "\n\t" . HTML::el('head')->setHtml($this->renderInlineMetaTags() . $this->renderInlineCss() . "\n\t") . "\n";
        $output .= $this->output;
        return $output;
    }

    /**
     * @todo dodelat!!!!!
     * @return string
     */
    private function getDocument()
    {
        if ($this->documentType === 'fragment') {
            return $this->output;
        } else {
            return $this->addHeader() . "\n</html>";
        }
    }

    /**
     * Magicky pristup k promenym v sablone.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->templateProperties[$name];
    }

    /**
     * Nastavuje promene do sablony.
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    public function __set($name, $value)
    {
        $this->templateProperties[$name] = $value;
        return $this;
    }

    /**
     * Zjisti jestli promena v sablone existuje.
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->templateProperties[$name]);
    }

    /**
     * Odnastavi promenou.
     *
     * @param string $name
     */
    public function  __unset($name)
    {
        unset($this->templateProperties[$name]);
    }

    public function __toString()
    {
        return $this->getDocument();
    }

    /**
     * Nastavi hodnotu vlastnosti command.
     *
     * @param Bobr_Command $command
     * @return Bobr_Page_Template
     */
    public function setCommand($command)
    {
        $this->command = new Bobr_Command($command);
        return $this;
    }

    /**
     * Nastavi vlastnost containerColection.
     *
     * @param Bobr_Page_ContainerColection $containerColection
     * @return Bobr_Page_Template
     */
    public function setContainerColection(Bobr_Page_ContainerColection $containerColection)
    {
        $this->containerColection = $containerColection;
        return $this;
    }

    /**
     * Nastavi druh stranky.
     *
     * @param string $value
     * @return Bobr_Page_Template
     */
    public function setDocumentType($value)
    {
        $this->documentType = $value;
        return $this;
    }
}