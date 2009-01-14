<?php

/**
 * Rozhrani pro DataObject.
 *
 * @author rbas
 */
interface Bobr_DataObjectInterface
{
    /**
     * Naimportuje do sebe data.
     *
     * @param array $record
     * @return Bobr_DataObject
     */
    public function importRecord(array $record);
    
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
     *
     * @param Object
     */
    public function importObject(Object $object);
}
