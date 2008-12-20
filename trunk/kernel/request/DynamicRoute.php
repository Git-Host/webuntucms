<?php
/**
 * Description of DynamicRoute
 *
 * @author rbas
 */
class DynamicRoute extends DataObject
{
    /**
     * Symbol jazyka.
     * (cs, en)
     *
     * @var string
     */
    private $langSymbol = '';

    /**
     * pole objektu Route.
     *
     * @var array
     */
    private $items = array();

    public function  __construct($langSymbol = '')
    {
        if (!empty($langSymbol)) {
            // @todo validovat pomoci objektu Lang
            $this->setLangSymbol($langSymbol)->load();
        }
    }

    public function load()
    {
        if (FALSE === $this->loadFromCache()) {
            $this->loadAll();
            $this->saveToCache();
        }
        return $this;
    }

    private function loadAll()
    {
        // @todo odchytavat vyjimky z dibi pro pripad neexistujici tabulky.
        $query = "SELECT `module_functions_id`, `webinstance_id`, `pageid_id`, `command` FROM `" . Config::DB_PREFIX . 'routedynamic_' . $this->langSymbol . "`";
        $record = dibi::query($query)->fetchAll();
        $this->importRecord($record);
    }

    public function importRecord(array $record)
    {
        foreach ($record as $key => $route) {
            $this->items[$key] = new Route;
            $this->items[$key]->importRecord($route);
        }
    }

    public function getCacheId()
    {
        return '/kernel/request/' .$this->langSymbol . '/' . $this->getClass();
    }

    /**
     * Vrati hodnotu vlastnosti langSymbol.
     *
     * @return string
     */
    public function getLangSymbol()
    {
        return $this->langSymbol;
    }

    /**
     * Nastavi hodnotu vlastnosti LangSymbol
     *
     * @param string $langSymbol
     * @return DynamicRoute
     */
    public function setLangSymbol($langSymbol)
    {
        $this->langSymbol = (string) $langSymbol;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti items
     *
     * @return array
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Nastavi hodnotu vlastnosti items.
     *
     * @param array $items
     * @return DynamicRoute
     */
    public function setItems(array $items)
    {
        $this->items = (array) $items;
        return $this;
    }
}