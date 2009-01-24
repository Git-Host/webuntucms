<?php
/**
 * Description of administration
 *
 * @author rbas
 */
class Modules_Administration_Exec extends Lib_AbstractModule
{
    protected function defaultAction()
    {
        return 'Vitej v bobroid';
    }

    protected function modularAction()
    {
        return $this->loadTemplate('template/default.phtml');
    }

    protected function navigationAction()
    {
        return $this->loadTemplate('template/main-menu.phtml');
    }

}