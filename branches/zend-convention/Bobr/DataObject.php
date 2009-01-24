<?php

/**
 * Description of Bobr_DataObject
 *
 * @author rbas
 */
abstract class Bobr_DataObject extends Object implements Bobr_DataObjectInterface
{
    /**
     * Uloziste dat
     *
     * @var string
     */
    protected $storage = '';
    
    /**
     * Konstanta na import dat v metode getRecordMap().
     * 
     * @var string
     */
    const RECORD_MAP_IMPORT = 'import';
    /**
     * Konstanta na export dat v metode getRecordMap().
     * 
     * @var string
     */
    const RECORD_MAP_EXPORT = 'export';

    /**
     * Naimportuje do sebe vlastnosti ktere jsou v seznamu importProperties.
     *
     * @param ArrayObject $record
     * @throws Bobr_IAException Pokud metoda getRecordMap nevrati pole.
     * @return Bobr_DataObject
     */
    public function importRecord(ArrayObject $record)
    {     
        $recordMap = $this->getRecordMap(self::RECORD_MAP_IMPORT);
        if (empty($recordMap)) {
        	throw new Bobr_IAException('Ve tride ' . $this->getClass() . ' neni nadefinovana metoda getRecordMap($type). Nemuzu naimportovat data.');
        }
        // Projdeme si record
        foreach ($record as $name => $value) {
            // Pokud klic existuje muzem ho nastavit
            if (isset($recordMap[$name])) {
                $methodName = 'set' . ucfirst($recordMap[$name]);
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
            $data = Bobr_Cache_Cache::flush($this->getCacheId(), $this->getStorage());
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
            return Bobr_Cache_Cache::save($this->getCacheId(), $this, $this->getStorage());
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
        return Bobr_Cache_Cache::delete($this->getCacheId(), $this->getStorage());
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
     * Vraci pole pro generovany import nebo export record.
     * 
     * @param string
     * @return array
     */
    protected abstract function getRecordMap($type);
    
    /**
     * Vrati pole na zaklade $type.
     * 
     * @param string $type
     * @param array $map
     * @return array
     */
    protected function returnMap($type, $map)
    {
    	switch ($type) {
    		case self::RECORD_MAP_EXPORT:
    			return array_flip($map);
    		case self::RECORD_MAP_IMPORT: // toto je schvalne
    		default:
    			return $map;
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
    protected function setStorage($storage = Bobr_Cache_Cache::FILE_STORAGE)
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
     * @return Bobr_DataObject
     */
    protected function setImportProperties(array $importProperties)
    {
        $this->importProperties = $importProperties;
        return $this;
    }

}