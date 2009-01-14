<?php
/**
 * Description of Process
 *
 * @author rbas
 */
class Kernel_Process extends Object
{

    /**
     * Symbol jazyka se kterym se pracuje.
     *
     * @var string
     */
    private $lang = '';


    /**
     * id stranky ktera se ma nacist.
     *
     * @var integer
     */
    private $pageId = 0;

    /**
     * Command ktery se ma provest.
     *
     * @var Command
     */
    private $command = NULL;

    private $webRoot = '';

    public function  __construct() {
        $this->init();
    }

    private function init()
    {
        $request = Kernel_Request_HttpRequest::getInstance();
        // Zjistime co mame za jazyk.
        $this->setLang();

        
        // Pokud je to ajaxovej request...
        if (TRUE === $request->isAjax()) {
            Kernel_Page_Template::getInstance()->setDocumentType('fragment');
        }

        // Je get prazdnej?
        $uri = Kernel_Request_HttpRequest::uri();
        if ($uri === '' || empty($uri)) {
            // Nastavime defaultni stranku.
            $this->setDefaultPage();
        } else {
            $this->setPage();
        }
    }

    /**
     * Nastavi lang sybmol dle pravidel.
     *
     * @return Process
     */
    private function setLang()
    {
        $config = new Kernel_Config_Config;
        switch ($config->remoteLangFrom) {
            case 'config':
                $this->lang = $config->defaultLang;
                $this->webRoot = $config->webRoot;
                break;
            case 'browser':
                throw new Kernel_ProcessException('fycura prebrani jazyku z prohlizece neni jeste napsana.');
                break;
            case 'uri':
                // Nactem si lang z GETu
                $lang = Kernel_Request_HttpRequest::lang();
                if (empty($lang)) {
                    // Lang byl prazdny presmerujem ho na url s defaultnim langem
                    $this->webRoot = $config->webRoot . $config->defaultLang . '/';
                    Kernel_Request_HttpRequest::redirect($this->webRoot);
                } else {
                    $this->webRoot = $config->webRoot . $config->defaultLang . '/';
                    $this->lang = $lang;
                }
                
                break;
            default:
                $this->lang = $config->defaultLang;
                $this->webRoot = $config->webRoot;
                break;
        }
        // @todo mela by probehnout nejake validace langu.
        Kernel_Session::getInstance()->lang = $this->lang;
        return $this;
    }

    private function setPage()
    {
        // Zjistime jestli se jedna o dynamickou routu.
        if (FALSE === $this->checkDynamicRoute()) {
            // Zjistime jestli se jedna o statickou routu.
            if (FALSE === $this->checkStaticRoute()) {
                $config = new Kernel_Config_Config;
                Lib_Messanger::addError('Byla zadana neexistujici adresa.');
                // @todo presmerovavat na chubovou stranku.
                Kernel_Request_HttpRequest::redirect($this->webRoot);
            }
        }
        print_RE('kakacek');
    }

    /**
     * Pokud se jedna o dynamickou routu nastavi ji a vrati TRUE.
     *
     * @return boolean
     */
    private function checkDynamicRoute()
    {
        $dynamicRoute = new Kernel_Request_DynamicRoute($this->lang);
        $uri = Kernel_Request_HttpRequest::uri();
        foreach ($dynamicRoute->items as $route) {
            // Pokud se to projde regularem mame lokalizovanou dinamickou routu.
            // Routa se ale jeste musi projet command validatorem. Jestli na ni ma user pravo.
            if (0 < preg_match('@' . $route->command . '@', $uri, $matches)) {
                $commandValidator = new Kernel_CommandValidator;
                $command = $commandValidator->getCommand($route->moduleFunctionId);
                // Pokud by byl command null znamenalo by to, ze uzivatel nema pravo na dany command.
                if (NULL !== $command) {
                    // Zjistime jestli jsme na spravne webInstanci
                    $webInstanceValidator = new Kernel_WebInstanceValidator;
                    if (FALSE === $webInstanceValidator->isCurrent($route->getWebInstanceId())) {
                        var_dump($webInstanceValidator->isCurrent($route->getWebInstanceId()));
                        Lib_Messanger::addNote('Url byla zadana chybne, presvedcte se zda-li jste na spravne strance.');
                        Kernel_Request_HttpRequest::redirect($this->webRoot);
                    }
                    // Pokud se uri neshoduje je v ni neco navic
                    if ($uri !== $matches[0]) {
                        Lib_Messanger::addNote('Url byla zadana chybne, presvedcte se zda-li jste na spravne strance.');
                        Kernel_Request_HttpRequest::redirect($this->webRoot . $matches[0]);
                    }
                    // Odstranime prvni polozku z pole, ta nas nezajima.
                    array_shift($matches);
                    // Mergneme lokalizovany command za vychozi.
                    $command = Lib_Tools::mergeCommand($command, $matches);
                    // A nastavime hodnoty pro dalsi praci.
                    $this->setCommand($command);
                    $this->setPageId($route->pageId);
                    return TRUE;
                } else {
                    Lib_Messanger::addNote('Nemate pravo na pristup na tuto stranku.');
                    Kernel_Request_HttpRequest::redirect($this->webRoot);
                    return FALSE;
                }
            }
        }

        return FALSE;
    }

    /**
     * Zjisti zda-li se jedna o statickou routu.
     * Pokud ano nastavi ji a vrati TRUE.
     *
     * @return boolean
     */
    private function checkStaticRoute()
    {
        $route = new Kernel_Request_Route;
        $uri  = Kernel_Request_HttpRequest::uri();
        if (NULL !== $route->loadByUri($uri, $this->lang)) {
            $this->setPageId($route->pageId);
            $this->setCommand($route->command);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Nastavni Defaultni stranku.
     */
    private function setDefaultPage()
    {
        $config = new Kernel_Config_Config;
        $this->setPageId($config->defaultPageId);
        $this->setLang($config->defaultLang);
    }

    /**
     * Vrati hodnotu vlatnosti pageID.
     *
     * @return integer
     */
    public function getPageId()
    {
        return $this->pageId;
    }
 
    /**
     * Nastavi vlastnost pageId.
     *
     * @param integer $pageId
     * @return Process
     */
    private function setPageId($pageId)
    {
        $this->pageId = (integer)$pageId;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti command
     *
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Nastavi vlastnost command.
     *
     * @param string $command
     * @return Kernel_Process
     */
    private function setCommand($command)
    {
        $this->command = new Kernel_Command($command);
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti lang.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
}