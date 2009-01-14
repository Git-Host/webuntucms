<?php
/**
 * Rozhrani pro kesove adaptery.
 *
 * @author rbas
 */
interface Kernel_Cache_CacheAdapterInterface
{
    /**
     * Nacteni dat z cache
     *
     * @param string $cacheId 
     */
    public function load($cacheId);

    /**
     * Ulozeni dat do cache
     *
     * @param string $cacheId
     * @param mixed $data 
     */
    public function write($cacheId, $data);

    /**
     * Smazani dat z cache
     *
     * @param string $cacheId 
     */
    public function delete($cacheId);

    /**
     * Smazani cele vetve
     *
     * @param string $dir
     */
    public function deleteDir($dir);

    /**
     * Je cachovany?
     *
     * @param string $cacheId
     */
    public function isCached($cacheId);

    /**
     * Vrati cestu k ulozisti dat
     *
     * @param string $cacheId 
     */
    public function getPath($cacheId);
}