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
     * @var strig
     */
    protected $command = '';

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
    protected $data = '';

    /**
     * Nastavi objektu zakladni vlastnosti
     *
     * @param string $command 
     */
    public function  __construct($command)
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
        $this->data = $this->{$this->action . 'Action'}();
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
        $command = explode('/', $this->command);
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
     * Vrati hodnotu vlastnosti command
     *
     * @return string
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
    protected function setCommand($command)
    {
        $this->command = (string)$command;
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
}