<?php

/**
 * Description of Cache
 *
 * @author rbas
 */
class Cache
{

    /**
     * File storage
     *
     * @var string
     */
    const FILE_STORAGE = 'File';

    /**
     * Nacte data z cache
     *
     * @param string $cacheId
     * @param string $mode
     * @return mixed
     */
    public static function flush($cacheId, $mode = self::FILE_STORAGE)
    {
        $className = $mode . 'Storage';
        $cacheAdapter = new $className;
        return $cacheAdapter->load(self::getCacheId($cacheId));
    }

    /**
     * Ulozi data do cache
     *
     * @param string $cacheId
     * @param mixed $content
     * @param string $mode
     * @return boolean
     */
    public static function save($cacheId, $content, $mode  = self::FILE_STORAGE)
    {
        $className = $mode . 'Storage';
        $cacheAdapter = new $className;
        return $cacheAdapter->write(self::getCacheId($cacheId), $content);
    }

    /**
     * Zjisti jestli data existujou
     *
     * @param string $cacheId
     * @param string $mode
     * @return boolen
     */
    public static function exists($cacheId, $mode = self::FILE_STORAGE)
    {
        $className = $mode . 'Storage';
        $cacheAdapter = new $className;
        return $cacheAdapter->isCached(self::getCacheId($cacheId));
    }

    /**
     * Smaze data z cache
     *
     * @param string $cacheId
     * @param string $mode
     * @return boolean
     */
    public static function delete($cacheId, $mode = self::FILE_STORAGE)
    {
        $className = $mode . 'Storage';
        $cacheAdapter = new $className;
        return $cacheAdapter->delete(self::getCacheId($cacheId));
    }

    /**
     * Smaze celou vetev cache
     *
     * @param strig $dir
     * @param strig $mode
     * @return boolean
     */
    public static function deleteDir($dir, $mode = self::FILE_STORAGE)
    {
        $className = $mode . 'Storage';
        $cacheAdapter = new $className;
        return $cacheAdapter->deleteDir(self::getCacheId($cacheId));
    }

    /**
     * Prida k identifikatoru cache informaci o webinstanci
     *
     * @param string $cacheId
     * @return string
     */
    private static function getCacheId($cacheId)
    {
        return Tools::getWebInstance() . '/' . $cacheId;
    }

}
