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
        $blockCommand = explode('/', $block->getCommand());
        $requestCommand = explode('/', $command);
        $moduleName = $blockCommand[0];
        if (FALSE === (!isset($blockCommand[1]) && ($blockCommand[0] === $requestCommand[0]))) {
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