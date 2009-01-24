<?php
/**
 * Description of Link
 *
 * @author rbas
 */
class Lib_Link extends Object
{
    /**
     * Vygeneruje link na webRoot.
     * Pozor pouzivat az po tom co se provede Process, pouzivaji se z nej hodnoty.
     *
     * @return string
     */
    public static function getWebRoot()
    {
        $config = new Config;
        $webRoot = $config->webRoot;
        if ($config->remoteLangFrom === 'uri') {
            $webRoot .= Bobr_Session::lang() . '/';
        }
        return $webRoot;
    }

    /**
     * Lokalizuje command, za predpokladu, ze preklad existuje.
     *
     * @param string $link
     * @return string
     */
    public static function build($link, $isStatic = FALSE)
    {
        if (FALSE === $isStatic) {
            // Nactem si lokalizovane linky a jejich matchovaci protejsky
            $linkCreator = new Lib_LinkCreator;
            // @todo logovat ktere linky se na dane pageId pouzivaji a cechovat to
            // at se neprovadi tento proces neustale dokola.
            $linkCreator->load();
            foreach ($linkCreator->getLinkPatterns() as $pattern) {
                if (0 < preg_match('@' . $pattern['pattern'] . '@i', $link, $matches)) {
                    array_shift($matches);
                    return self::getWebRoot() . Lib_Tools::mergeCommand($pattern['localize'], $matches);
                }
            }
            return 'Link neni lokalizovan';
        } else {
            return self::getWebRoot() . $link;
        }
    }
}