<?php
/**
 * Description of LangList
 *
 * @author rbas
 */
class Bobr_LangList extends Bobr_DataObject
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
     * @return Bobr_LangList
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
     * @param ArrayObject $record
     * @return Bobr_LangList
     */
    public function importRecord(ArrayObject $record)
    {
        foreach ($record as $lang) {
            $this->items[$lang['id']] = new Bobr_Lang;
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
     * @return Bobr_LangList
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