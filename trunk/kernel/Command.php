<?php
/**
 * Description of Command
 *
 * @author rbas
 */
class Command
{
    /**
     * Prikaz pro modul.
     *
     * @var string
     */
    private $command = '';

    public function __construct($command)
    {
        $this->setCommand($command);
    }

    /**
     * Nastavi hodnotu vlastnosti commnad
     *
     * @param string $command
     * @return Command
     */
    public function setCommand($command)
    {
        $this->command = (string)$command;
        return $this;
    }

    public function toArray()
    {
        return explode('/', $this->command);
    }

    public function getModule()
    {
        $command = explode('/', $this->command);
        return $command[0];
    }

    public function __toString()
    {
        return $this->command;
    }
}