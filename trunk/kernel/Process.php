<?php
/**
 * Description of Process
 *
 * @author rbas
 */
class Process extends Object
{
    /**
     * Promena getu ktera ovlada bobra ;)
     *
     * @var string
     */
    const VARIABLE = 'q';

    private $lang = '';

    /**
     * Informace jestli jsou zajisteny potrebne data
     * @todo toto neni dobre reseni navrhnot lepsi.
     *
     * @var boolean
     */
    private $isCreated = FALSE;

    /**
     * id stranky ktera se ma nacist.
     *
     * @var integer
     */
    private $pageId = 0;

    /**
     * Command ktery se ma provest.
     *
     * @var string
     */
    private $command = '';

    public function  __construct() {
        $this->init();
    }

    private function init()
    {
        // Zjistime co mame za jazyk.
        $this->setLang();

        $request = HttpRequest::getInstance();
        // Pokud je to ajaxovej request...
        if (TRUE === $request->isAjax()) {
            throw new Exception('Ajaxove requesty nejsou implementovany.');
        }

        // Je get prazdnej?
        $uri = substr($request->getUri(), 1);
        if (empty($uri)) {
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
        $this->lang = Session::getInstance()->lang;
        if (empty($this->lang)) {
            $config = new Config;
            switch ($config->remoteLangFrom) {
                case 'config':
                    $this->lang = $config->defaultLang;
                    break;
                case 'browser':
                    throw new Exception('fycura neni jeste napsana.');
                    break;
                case 'uri':
                    throw new Exception('fycura neni jeste napsana.');
                    break;
                default:
                    $this->lang = $config->defaultLang;
                    break;
            }
        }
        // @todo mela by probehnout nejake validace langu.
        Session::getInstance()->lang = $this->lang;
        return $this;
    }

    private function setPage()
    {
        // Zjistime jestli se jedna o dynamickou routu.
        if (FALSE ===$this->checkDynamicRoute()) {
            // Zjistime jestli se jedna o statickou routu.
            if (FALSE === $this->checkStaticRoute()) {
                $config = new Config;
                HttpRequest::redirect($config->webRoot);
            }
        }
    }

    /**
     * Pokud se jedna o dynamickou routu nastavi ji a vrati TRUE.
     *
     * @return boolean
     */
    private function checkDynamicRoute()
    {
        $dynamicRoute = new DynamicRoute($this->lang);
        $uri = substr(HttpRequest::getInstance()->getUri(), 1);
        foreach ($dynamicRoute->items as $route) {
            // Pokud se to projde regularem mame lokalizovanou dinamickou routu.
            // Routa se ale jeste musi projet command validatorem. Jestli na ni ma user pravo.
            if (0 < preg_match('@' . $route->command . '@', $uri, $matches)) {
                $commandValidator = new CommandValidator;
                $command = $commandValidator->getCommand($route->moduleFunctionId);
                // Pokud by byl command null znamenalo by to, ze uzivatel nema pravo na dany command.
                if (NULL !== $command) {
                   // var_dump($uri !== $matches[0]);
                    // Pokud se uri neshoduje je v ni neco navic
                    if ($uri !== $matches[0]) {
                        Messanger::addNote('Url byla zadana chybne, presvedcte se zda-li jste na spravne strance.');
                        HttpRequest::redirect('/'.$matches[0]);
                    }
                    // Odstranime prvni polozku z pole, ta nas nezajima.
                    array_shift($matches);
                    // Mergneme lokalizovany command za vychozi.
                    $command = $this->mergeCommand($command, $matches);
                    // A nastavime hodnoty pro dalsi praci.
                    $this->setCommand($command);
                    $this->setPageId($route->pageId);

                    return TRUE;
                } else {
                    Messanger::addNote('Nemate pravo na pristup na tuto stranku.');
                    $config = new Config;
                    HttpRequest::redirect($config->webRoot);
                    return FALSE;
                }
            }
        }

        return FALSE;
    }



    /**
     * Vlozi do CommandPattern argumenty.
     *
     * @param string $commandPattern Command ktery se ma uzpusobit.
     * @param array $matches Pole argumentu ktere se maji margnout do commandu.
     * @return string
     */
    private function mergeCommand($commandPattern, $matches)
    {
        // Rozzerem si command.
        $explodeCommand = explode('/', $commandPattern);
        $matchCounter = 0;
        $commandCounter = 0;
        foreach ($explodeCommand as $value) {
            // Pokud jsme na necem kde je zavorka je to promenlivy argument.
            if (0 < preg_match('@^\(.*@', $value)) {
                // Zmenime jeho hodnotu.
                $explodeCommand[$commandCounter] = $matches[$matchCounter];
                $matchCounter ++;
            }
            $commandCounter ++;
        }
        return implode('/', $explodeCommand);
    }

    /**
     * Zjisti zda-li se jedna o statickou routu.
     * Pokud ano nastavi ji a vrati TRUE.
     *
     * @return boolean
     */
    private function checkStaticRoute()
    {
        $route = new Route;
        $uri  = substr(HttpRequest::getInstance()->getUri(), 1);
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
        $config = new Config;
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
        $this->pageId = (integer) $pageId;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti command
     *
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Nastavi vlastnost command.
     *
     * @param string $command
     * @return Process
     */
    private function setCommand($command)
    {
        $this->command = (string) $command;
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