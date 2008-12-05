<?php
/**
 * Description of FileStorage
 *
 * @author rbas
 */
class FileStorage extends Object implements ICacheAdapter
{
    /**
     * Cesta k ulozisti dat.
     * 
     * @var string
     */
    private static $path = '';

    /**
     * Nacte data z cache, pokud neexistujou vrati NULL
     *
     * @param string $cacheId
     * @return mixed
     */
    public function load($cacheId)
    {
        if (file_exists($this->getPath($cacheId))) {
            $data = @file_get_contents($this->getPath($cacheId));
        } else {
            return NULL;
        }     

       return unserialize($data);
    }

    /**
     * Ulozi data do cache
     *
     * @param string $cacheId
     * @param mixed $data
     * @return boolean
     */
    public function write($cacheId, $data)
    {
        $filename = $this->getPath($cacheId);
        $dir = dirname($filename);
		if (!is_dir($dir)) {
			mkdir($dir, 0771, TRUE);
		}
        return file_put_contents($filename, serialize($data)) !== FALSE;
    }

    /**
     * Smaze konkretni data z cache
     *
     * @param string $cacheId
     * @return boolean
     */
    public function delete($cacheId)
    {
        if (file_exists($this->getPath($cacheId))) {
           return unlink($this->getPath($cacheId));
       }
       return TRUE;
    }

    /**
     * Smaze vetev
     *
     * @param string $cacheId
     */
    public function deleteDir($cacheId)
    {
        throw new Exception('Metda neni implementovana');
    }

    /**
     * Zjisti zda zaznam existuje.
     *
     * @param string $cacheId
     * @return boolean
     */
    public function isCached($cacheId)
    {
        if (file_exists($this->getPath($cacheId))) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Vrati cestu k ulozisti, pokud neni nastavena nastavi ji.
     *
     * @param string $cacheId
     * @return string
     */
    public function getPath($cacheId)
    {
        if (empty(self::$path)) {
            $this->setPath();
        }
        return __WEB_ROOT__ .self::$path . $cacheId;
    }

    /**
     * Nastavi cestu k ulozisti dat
     * 
     * @return FileStorage 
     */
    private function setPath()
    {
        $config = new Config;
        self::$path = $config->FileStoragePath;
        return $this;
    }
}