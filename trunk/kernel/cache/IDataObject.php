<?php

/**
 * Rozhrani pro DataObject.
 *
 * @author rbas
 */
interface IDataObject
{
    
    /**
     * Vraci identifikator kese
     */
    public function getCacheId();

    /**
     * Nacte data z kese
     */
    public function loadFromCache();

    /**
     * Ulozi data do kese
     */
    public function saveToCache();

    /**
     * Naimportuje objekt
     */
    public function importObject(Object $object);
}
