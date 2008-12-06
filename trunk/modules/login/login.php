<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Login
 *
 * @author rbas
 */
class Login
{
    public $command;

    public function  __construct($command) {
        $this->command = $command;
        require_once 'template.phtml';
    }

    public function  __toString() {
        return print_R($this,TRUE);
    }
}