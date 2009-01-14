<?php
/**
 * Description of ModuleDelegator
 *
 * @author rbas
 */
class Kernel_Page_ModuleDelegator extends Object
{
    /**
     * Vytvori na zaklade objektu Block obsah.
     * Pokud Block nema zadany vlastni command pokusi se predat co command
     * z requestu, za predpokladu ze se jedna o shodny modul.
     *
     * @param Kernel_Page_Block $block
     * @param string $command
     * @return string
     */
    public function createBlock(Kernel_Page_Block $block, $command)
    {
        $blockCommand = $block->getCommand()->toArray();
        
        $requestCommand = explode('/', $command);
        $moduleName = $block->getCommand()->getModule();
        if (FALSE === (!isset($blockCommand[1]) && ($moduleName === $requestCommand[0]))) {
           $command = $block->getCommand();
        }
        ob_start();
        // @todo udelat nejake kontrolovani bo vyhazovat vyjimku
        require_once __WEB_ROOT__ . '/Modules/' . ucfirst($moduleName) . '/Exec.php';
        $className = 'Modules_' . ucfirst($moduleName) . '_Exec';
        echo new $className($command);
            
        $contents = ob_get_contents();
        ob_end_clean();


        return $contents;
    }
}