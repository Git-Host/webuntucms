<?php
/**
 * Description of LangList
 *
 * @author rbas
 */
class LangList extends DataObject
{

    /**
     * Pole objektu Lang
     *
     * @var array
     */
    private $items = array();

    public function  __construct() {
        $this->load();
    }

    /**
     * Nacte dostupne jazyky.
     *
     * @return LangList
     */
    private function load()
    {
        if (! $this->loadFromCache()) {
            $query = "SELECT `id`, `symbol`, `country` FROM `" . Config::DB_PREFIX . "lang`";
            $record = dibi::query($query)->fetchAll();
            if (!empty($record)) {
                $this->importRecord($record);
            }
            $this->saveToCache();
        }
        return $this;
    }

    /**
     * Naimportuje do sebe objekty Lang podle databazoveho recordu.
     *
     * @param array $record
     * @return LangList
     */
    private function importRecord(array $record)
    {
        foreach ($record as $lang) {
            $this->items[$lang['id']] = new Lang;
            $this->items[$lang['id']]->importRecord($lang);
        }
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti items.
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
     * @return LangList
     */
    public function setItems(array $items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Vrati cachovy identifikator
     *
     * @return string
     */
    public function getCacheId()
    {
        return '/kernel/' . $this->getClass();
    }
}