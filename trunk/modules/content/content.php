<?php
/**
 * Description of content
 *
 * @author rbas
 */
class Content
{
    public $command = '';
    public function  __construct($command) {
        $this->command = $command;
    }

    public function  __toString() {
        return print_r($this,true);
    }
}