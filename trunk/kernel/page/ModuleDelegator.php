<?php
/**
 * Description of ModuleDelegator
 *
 * @author rbas
 */
class ModuleDelegator extends Object
{
    /**
     * Vytvori na zaklade objektu Block obsah.
     * Pokud Block nema zadany vlastni command pokusi se predat co command
     * z requestu, za predpokladu ze se jedna o shodny modul.
     *
     * @param Block $block
     * @param string $command
     * @return string
     */
    public function createBlock(Block $block, $command)
    {
        $blockCommand = $block->getCommand()->toArray();
        
        $requestCommand = explode('/', $command);
        $moduleName = $block->getCommand()->getModule();
        if (FALSE === (!isset($blockCommand[1]) && ($moduleName === $requestCommand[0]))) {
           $command = $block->getCommand();
        }
        ob_start();
        // @todo udelat nejake kontrolovani bo vyhazovat vyjimku
        require_once __WEB_ROOT__ . '/modules/' . $moduleName . '/' . $moduleName . '.php';
        echo new $moduleName($command);
            
        $contents = ob_get_contents();
        ob_end_clean();


        return $contents;
    }
}