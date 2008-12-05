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

}