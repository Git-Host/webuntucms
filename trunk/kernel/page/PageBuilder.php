<?php
/**
 * Description of PageBuilder
 *
 * @author rbas
 */
class PageBuilder extends Page
{

    private $bufferTemplate = '';

    private $command = '';

    public function createPage($command)
    {
        $this->setTitle('BOBR 2.0 devel cr.3');
        $this->command = $command;
        $this->cssList->addCss('themes/default/css/console.css');
        // Sestavime kontainer list pokud jeste nebyl sestaven.
        $this->getContainerList();
        // Loadneme vsechny popisky z page
        DescriptionList::getInstance()->load();
        // Nactem templatu
        $this->loadBaseTemplate();
    }

    public function getContainer($container)
    {
        // Overime Container jestli je pro tohoto uzivatele na strance
        if (isset($this->containerList->items[$container])) {
            // Je tak ho naplnime
            return $this->createContainer($this->getContainerList()->items[$container]);
        } else {
            return NULL;
        }
    }

    /**
     * Vrati obsah Containeru.
     * Zavola do prislusnych blocku moduly, ktere mu vrati obsah.
     *
     * @param Container $container
     * @return string
     */
    private function createContainer(Container $container)
    {
        $output = '';
        foreach ($container->blocksList->items as $block) {
            $moduleDelegator = new ModuleDelegator;
            $description = DescriptionList::getInstance()->getDescription($block->descriptionId);
            $output .= "\n<div title=\"" .$description->getDescription() . "\">\n";
            $output .= "<h3>" . $description->getTitle() . "</h3>\n";
            $output .= $moduleDelegator->createBlock($block, $this->command);
            $output .= "\n</div>";
        }
        return $output;
    }

    public function getMessanger()
    {
        return Messanger::flush(TRUE);
    }

    private function loadBaseTemplate()
    {
        $config = new Config;
        $template = __WEB_ROOT__ . $config->share . $this->template;
        if (file_exists($template)) {
            ob_start();
                require_once $template;
            $this->bufferTemplate = ob_get_contents();
            ob_end_clean();
        } else {
            echo 'Template neexistuje ->' . $template;
        }
    }

    private function getHeader()
    {
        
        $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>' . $this->title . '</title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        ' . $this->cssList->getCss() . '
        </head>
        ';
        return $output;
    }
    
    public function  __toString() {
        $document = $this->getHeader() . $this->bufferTemplate;
        return $document;
    }
}