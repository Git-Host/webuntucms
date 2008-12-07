<?php
/**
 * Description of PageBuilder
 *
 * @author rbas
 */
class PageBuilder extends Page
{

    private $bufferHeader = '';

    private $command = '';

    public function createPage($command)
    {
        $this->setTitle('Chromak bosak');
        $this->command = $command;
        $this->cssList->addCss('picunda');
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
        $template = __WEB_ROOT__ . '/' . $this->template;
        if (file_exists($template)) {
            ob_start();
                require_once $template;
            $this->bufferHeader = ob_get_contents();
            ob_end_clean();
        } else {
            echo 'pica neexistuje ->' . $template;
        }
    }
    
    public function  __toString() {
        return $this->bufferHeader;
    }
}