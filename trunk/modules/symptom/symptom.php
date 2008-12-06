<?php
/**
 * Description of symptom
 *
 * @author rbas
 */
class Symptom
{
    private $command;

    public function  __construct($command) {
        $this->command = $command;
    }

    public function  __toString() {
        return print_R($this,TRUE);
    }
}