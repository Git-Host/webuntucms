<?php
/**
 * Description of content
 *
 * @author rbas
 */
class Content
{
    public $command = '';
    public $data;

    public function  __construct($command) {
        $this->command = $command;
        $this->init();
    }

    private function init()
    {
        $this->data = Link::build('content/show/45');
        require_once 'template/default.phtml';
    }

    public function  __toString()
    {
        //$output = print_r($this,true);
        //return $output;
        return '';
    }
}