<?php
/**
 * Description of content
 *
 * @author rbas
 */
class Content extends AbstractModule
{
    protected function showAction()
    {
        $this->data = Link::build('content/show/45');
        require_once 'template/default.phtml';
    }

    protected function defaultAction()
    {
        echo 'Vitej v bobroid';
    }

    public function  __toString()
    {
        //$output = print_r($this,true);
        //return $output;
        return '';
    }
}