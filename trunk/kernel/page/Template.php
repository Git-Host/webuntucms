<?php
/**
 * Description of Template
 *
 * @author rbas
 */
class Template
{
    /**
     * Objekt ContainerColection.
     *
     * @var ContainerColection
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
     * Object
     *
     * @var Command
     */
    private $command = NULL;

    /**
     * Vlastni instance.
     *
     * @var Template
     */
    private static $instace = NULL;


    /**
     * Vrati instanci tridy Template.
     *
     * @return Template
     */
    public static function getInstance()
    {
        if (NULL === self::$instace) {
            self::$instace = new Template;
        }     
        return self::$instace;
    }

    private function __construct()
    {}

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
            throw new TemplateException('Sablona ' . $fileName . ' neexistuje.');
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
                $moduleDelegator = new ModuleDelegator;
                $output = "\n<div class=\"block " . $block->getId() . "\">\n";
                $output .= $moduleDelegator->createBlock($block, $this->command);
                $output .= "\n</div>";
            }
            return $output;
        } elseif (empty($this->containerColection)) {
            throw new TemplateException('V Template neni nastaven objekt ContainerColection. Nemuze se vypsat stranka.');
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
        self::getInstance()->templateProperties[$name] = $value;
    }


    /**
     * Prida dalsi kaskadovy stylopis do hlavicky.
     *
     * @param string $link
     */
    public static function addCss($link)
    {
        self::getInstance()->css[] = $link;
    }

    /**
     * Vypise linky z vlastnosti css a prida jim html nalezitosti pro nacitani stylopisu.
     *
     * @return string
     */
    private function getCss()
    {
        $output = '';
        $config = new Config;
        foreach ($this->css as $css) {
            $output .= "\t<link href=\"" . $config->share . $css . "\" rel=\"stylesheet\" type=\"text/css\" />\n";
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
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>' . $this->templateProperties['webTitle'] . '</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        ' . $this->getCss() . '
        </head>
        ' . $this->output;
        return $output;
    }

    /**
     * @todo dodelat!!!!!
     * @return string
     */
    private function getDocument()
    {
        return $this->addHeader();
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
     * @param Command $command
     * @return Template
     */
    public function setCommand($command)
    {
        $this->command = new Command($command);
        return $this;
    }

    /**
     * Nastavi vlastnost containerColection.
     *
     * @param ContainerColection $containerColection
     * @return Template
     */
    public function setContainerColection(ContainerColection $containerColection)
    {
        $this->containerColection = $containerColection;
        return $this;
    }
}