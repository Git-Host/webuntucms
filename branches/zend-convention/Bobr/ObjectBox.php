<?php
/**
 * Skladacka objektu. Tato trida je podobna jako Bobr_Colection, ale nekesuje se
 * Vyuziva kese jednotlivych objektu. 
 * Pokud maji vnitrni objekty byt volany z venci musi se jim nadefinovat verejny getter a popripade
 * setter.
 * ! POZOR Pri ukladani objektu se stripuje podtrzitko ( _ ), takze vnitrne se daji volat magicky objekty
 * takto $this->bobrUser.
 * 
 * Objekt je take iterovan postupne po importovanych objektech. Da se tedy prochazet cyklem.
 * 
 * @example Vlozily jsme do tridy objekt Bobr_User a chcem aby byl pristupny z venku.
 * Nadefinujeme mu getter:
 * public function getBobrUser()
 * {
 * 		return $this->bobrUser
 * }
 * Obdobne to je se setterem.
 * 
 * @author rbas
 *
 */
class Bobr_ObjectBox implements Iterator, Countable
{
	/**
	 * Seznam objektu.
	 * 
	 * @var mixed
	 */
	private $objects;
	/**
     * Vrati hodnotu na aktualni pozici.
     *
     * @return integer
     */
	private $index;
	
	/**
	 * Prida objekt do kolekce.
	 * 
	 * @param Object $object
	 * @return Bobr_ObjectBox
	 */
	public function addObject(Object $object)
	{
		$this->objects[str_replace('_', '', $object->getClass())] = $object;
		return $this;
	}
		
	/**
	 * Vrati volany object.
	 * 
	 * @param string $object
	 * @return mixed
	 */
	public function __get($object)
	{
		$name = ucfirst($object);
		$m = 'get' . $name;
		if (self::hasAccess($this, $m)) {
			return $this->objects[$name];	
		}
				
		if (isset($this->objects[$name])) {
			throw new Bobr_Access_Exception('Volany objekt ' . $name . ' v objektu ' . get_class($this) . ' je privatni.');
		} else {
			throw new Bobr_Access_Exception('Volany objekt ' . $name . ' v objektu ' . get_class($this) . ' neexistuje.');
		}
	}
	/**
	 * Nastavi objektu hodnoty
	 * 
	 * @param string $object
	 * @param mixed $value
	 * @throws Bobr_Access_Exception Pokud volany objekt neexistuje.
	 * @return Bobr_ObjectBox
	 */
	public function __set($object, $value)
	{
		$name = ucfirst($object);
		$m = 'set' . $name;
		if (self::hasAccess($this, $m)) {
			$this->objects[$name] = $value;
			return $this;	
		}
				
		if (isset($this->objects[$name])) {
			throw new Bobr_Access_Exception('Volany objekt ' . $name . ' v objektu ' . get_class($this) . ' je privatni.');
		} else {
			throw new Bobr_Access_Exception('Volany objekt ' . $name . ' v objektu ' . get_class($this) . ' neexistuje.');
		} 
	}
	/**
	 * Zjisti jestli existuje volany objekt
	 * 
	 * @param string $object
	 * @return boolean
	 */
	public function __isset($object)
	{
		return isset($this->objects[$object]);
	}
	/**
	 * Vrati boolean pokud volana funkce existuje a je verejna.
	 * 
	 * @param mixed $object
	 * @param string $methodName
	 * @return boolean
	 */
	private static function hasAccess($object, $methodName)
	{
		static $cache;
		$className = get_class($object);
		if (!isset($cache[$className])) {
			// @todo Rychlost: zbytecne reflektovat cely objekt staci v cyklu projet vsechny funkce
			$rm = new ReflectionObject($object);
			foreach ($rm->getMethods() as $method) {
				if ($method->isPublic() && !$method->isStatic()) {
					$cache[$className][str_replace('_', '', $method->name)] = TRUE;
				}
			}	
		}
		return isset($cache[$className][$methodName]);
	}
	/**
     * Vrati hodnotu na aktualni pozici.
     *
     * @return mixed
     */
	public function current() 
	{
		return current($this->objects);
	}
	/**
     * Presune iterator na dalsi dvojci klic/hodnota.
     */
	public function next() 
	{
		next($this->objects);
		$this->index++;
	}
	/**
     * Vraci boolen, podle toho zda seznam obsahuje dalsi hodnoty.
     * Pouziva se pred volanim metod current() a key().
     *
     * @return boolean
     */
	public function key() 
	{
		return key($this->objects);
	}
	/**
     * Vraci boolen, podle toho zda seznam obsahuje dalsi hodnoty.
     * Pouziva se pred volanim metod current() a key().
     *
     * @return boolean
     */
	public function valid() 
	{
		return $this->index < $this->count();
	}
	/**
     * Previne iterator na zacatek seznamu a nastavi vlastnost index na 0.
     */
	public function rewind() 
	{
		reset($this->objects);
        $this->index = 0;
	}
	/**
     * Vrati pocet objektu.
     *
     * @return integer
     */
	public function count()
	{
		return count($this->objects);
	}
}