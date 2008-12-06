<?php
/**
 * Description of pageid
 *
 * @author rbas
 */
class Pageid
{
    private $command;

    public function  __construct($command) {
        $this->command = $command;
    }

    public function  __toString() {
        return print_R($this,TRUE);
    }
}