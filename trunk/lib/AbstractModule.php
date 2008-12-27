<?php
/**
 * Description of Module
 *
 * @author rbas
 */
abstract class AbstractModule extends Object implements IModule
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
     * @param Command $command
     */
    public function  __construct(Command $command)
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
     * @return Module
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
        Template::add($name, $value);
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
        $template = Template::getInstance();
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
        return __WEB_ROOT__ . '/modules/' . strtolower($this->getClass()) . '/' . $fileName;
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
    protected function setCommand(Command $command)
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