<?php
/**
 * Description of menu
 *
 * @author rbas
 */
class Menu
{
    private $command;

    public function  __construct($command) {
        $this->command = $command;
    }

    public function  __toString() {
        return print_R($this,TRUE);
    }
}