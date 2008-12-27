<?php
/**
 * Description of content
 *
 * @author rbas
 */
class Content extends AbstractModule
{
    protected function defaultAction()
    {
        return 'Vitej v bobroid';
    }

    protected function showAction()
    {
        return $this->loadTemplate('template/default.phtml');
    }

    protected function newAction()
    {
        return 'Udelat novej clanek';
    }
}