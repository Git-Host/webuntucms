<?php
/**
 * Description of menu
 *
 * @author rbas
 */
class Modules_Menu_Exec
{
    private $command;

    public function  __construct($command) {
        $this->command = $command;
    }

    public function  __toString() {
        return print_R($this,TRUE);
    }
}