<?php

/**
 * Description of DataObject
 *
 * @author rbas
 */
abstract class DataObject extends Object implements IDataObject
{
    /**
     * Uloziste dat
     *
     * @var string
     */
    protected $storage = '';

    /**
     * Pole nazvu vlastnosti a jeji databazovych protejsku.
     * Pozor musi zde byt kazda privatni vlastnost.
     *
     * @example array('block_id' => 'blockId');
     * @var array
     */
    protected $importProperties = array();

    /**
     * Naimportuje do sebe vlastnosti ktere jsou v seznamu importProperties.
     *
     * @param array $record
     * @throws DataObjectIAException Pokud je vlastnost importProperties prazdna.
     * @return DataObject
     */
    public function importRecord(array $record)
    {
        if (empty($this->importProperties)) {
            throw new DataObjectIAException('Ve tride ' . $this->getClass() . ' neni nadefinovano pole $importProperties. Nemuzu naimportovat data.');
        }
        // Projdeme si record
        foreach ($record as $name => $value) {
            // Pokud klic existuje muzem ho nastavit
            if (isset($this->importProperties[$name])) {
                $methodName = 'set' . ucfirst($this->importProperties[$name]);
                $this->$methodName($value);
            }
        }
        return $this;
    }
    
    /**
     * Nacist se z cache
     *
     * @return boolean
     */
    public function loadFromCache()
    {
        $config = new Config;
        if (TRUE === $config->CacheMode) {
            $data = Cache::flush($this->getCacheId(), $this->getStorage());
            if (NULL !== $data) {
                $this->importObject($data);
                return TRUE;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * Ulozit se do cache
     *
     * @return boolen
     */
    public function saveToCache()
    {
        $config = new Config;
        if (TRUE === $config->CacheMode) {
            return Cache::save($this->getCacheId(), $this, $this->getStorage());
        }

        return FALSE;
    }

    /**
     * Smazani se z cache
     *
     * @return boolean
     */
    public function deleteFromCache()
    {
        return Cache::delete($this->getCacheId(), $this->getStorage());
    }

    /**
     * Naimportovani objektu z cache
     *
     * @param mixed $object
     */
    public function importObject(Object $object)
    {
        // Jedná se o stejnou třídu?
		if ($object->getClass() === $this->getClass()) {
			// Překopíruj všechny proměnné
			// Přes reflection překopírujeme i privátní proměnné
			$reflection = new ReflectionObject($object);
			foreach ($reflection->getProperties() as $property) {
				$name = $property->getName();
				$this->$name = $object->$name;
			}
		}
    }

    /**
     * Vrati cestu k ulozisti dat
     *
     * @return string
     */
    protected function getStorage()
    {
        if (empty($this->storage)) {
            $this->setStorage();
        }
        
        return $this->storage;
    }

    /**
     * Nastavi uloziste
     *
     * @param string $storage
     * @return mixed
     */
    protected function setStorage($storage = Cache::FILE_STORAGE)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti importProperties.
     *
     * @return array
     */
    protected function getImportProperties()
    {
        return $this->importProperties;
    }
    
    /**
     * Nastavi hodnotu vlastnosti importProperties.
     *
     * @param array $importProperties
     * @return DataObject
     */
    protected function setImportProperties(array $importProperties)
    {
        $this->importProperties = $importProperties;
        return $this;
    }

}