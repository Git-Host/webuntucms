<?php
/**
 * Description of LinkCreator
 *
 * @author rbas
 */
class LinkCreator extends DataObject
{
    private $linkPatterns = array();
    
    private static $lang = '';

    public function load()
    {
        if (! $this->loadFromCache()) {
            if (empty(self::$lang)) {
                throw new ErrorException($this->getClass() . ' nema nastaven jazyk. Nemuze tvorit linky.');
            }

            $query = "SELECT  mf.`func` as `pattern`, dr.`command` as `localize`
                FROM `" . Config::DB_PREFIX . "module_functions` mf
                JOIN `" . Config::DB_PREFIX . "routedynamic_" . self::$lang . "` dr ON mf.`id` = dr.`module_functions_id`
                ORDER BY mf.`module_id`, mf.`id`";
            $record = dibi::query($query)->fetchAssoc('pattern');
            if (!empty($record)) {
                $this->linkPatterns = $record;
            }
        }
    }

    public function getLinkPatterns()
    {
        return $this->linkPatterns;
    }

    public static function setLang($lang)
    {
        // @todo provadet kontrolu jazyka.
        self::$lang = (string) $lang;
    }

    public function getCacheId()
    {
        return '/' . $this->getClass();
    }
}