<?php
/**
 * Description of Module
 *
 * @author rbas
 */
abstract class Lib_AbstractModule extends Object implements Lib_IModule
{
    /**
     * Command.
     *
     * @var Command
     */
    protected $command = NULL;

    /**
     * Akce ktera se ma provadet.
     *
     * @var string
     */
    protected $action = '';

    /**
     * Parametry pro akci
     *
     * @var array
     */
    protected $params = array();

    /**
     * Vystup modulu do sablony
     *
     * @var string
     */
    protected $output = '';

    /**
     * Nastavi objektu zakladni vlastnosti
     *
     * @param Bobr_Command $command
     */
    public function  __construct(Bobr_Command $command)
    {
        $this->setCommand($command)
            ->assignCommand()
            ->init();
    }

    /**
     * Vola se hned po konstruktoru
     */
    protected function init()
    {
        $this->output = $this->{$this->action . 'Action'}();
    }
    
    /**
     * Defaultni akce.
     */
    abstract protected function defaultAction();

    /**
     * Zpracuje command a nastavi vlastnosti objektu.
     *
     * @return Bobr_AbstractModule
     */
    protected function assignCommand()
    {
        $command = $this->command->toArray();
        array_shift($command);
        if (isset($command[0])) {
            $this->setAction($command[0]);
            array_shift($command);
            if (isset($command[0])) {
                $this->setParams($command);
            }
        } else {
            $this->action = 'default';
        }

        return $this;
    }

    /**
     * Prida do sablony promenou a jeji hodnotu.
     *
     * @param string $name
     * @param mixed $value
     * @return mixed
     */
    protected function addToTemplate($name, $value)
    {
        Bobr_Page_Template::add($name, $value);
        return $this;
    }

    /**
     * Nacte sablonu.
     *
     * @param string $fileName Cesta se udava od korenoveho adresare modulu.
     * @return string
     */
    protected function loadTemplate($fileName)
    {
        $template = Bobr_Page_Template::getInstance();
        return $template->load($this->getFileName($fileName));
    }

    /**
     * Vrati absolutni cestu k souboru.
     *
     * @param string $fileName
     * @return string
     */
    protected function getFileName($fileName)
    {
        $path = explode('_', $this->getClass());
        return __WEB_ROOT__ . '/' . str_replace('Exec', '', implode('/', $path)) . $fileName;
        return __WEB_ROOT__ . '/Modules/' . strtolower($this->getClass()) . '/' . $fileName;
    }

    /**
     * Vrati hodnotu vlastnosti command
     *
     * @return Command
     */
    protected function getCommand()
    {
        return $this->command;
    }

    /**
     * Nastavi hodnotu vlastnosti command
     *
     * @param string $command
     * @return Module
     */
    protected function setCommand(Bobr_Command $command)
    {
        $this->command = $command;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti action
     *
     * @return string
     */
    protected function getAction()
    {
        return $this->action;
    }

    /**
     * Nastavi hodnotu vlastnosti action
     *
     * @param string $action
     * @return Module
     */
    protected function setAction($action)
    {
        $this->action = (string)$action;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti params
     *
     * @return array
     */
    protected function getParams()
    {
        return $this->params;
    }

    /**
     * Nastavi hodnotu vlastnosti params
     *
     * @param array $params
     * @return Module
     */
    protected function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    public function  __toString()
    {
        return $this->output;
    }
}