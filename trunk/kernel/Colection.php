<?php
/**
 * Slouzi pro import objektu do sebe.
 * Potomci teto tridy modou do sebe importovat objkety pomoci metody importColection.
 * Objekt je potom iterovan.
 *
 * @author rbas
 */
abstract class Colection extends DataObject implements Countable, Iterator, ArrayAccess
{
    /**
     * Kolekce objektu v poli.
     *
     * @var array
     */
    protected $colection = array();

    /**
     * Nazev tridy ktera se vklada do kolekce.
     *
     * @var string
     */
    protected $colectionClass = '';

    /**
     * Index iterovaneho pole.
     *
     * @var integer
     */
    private $index = 0;

    /**
     * Naimportuje do sebe kolekci objektu.
     *
     * @param array $record
     * @throws ColectionIAException Pokud neni vyplnena vlastnost $colectionClass.
     * @throws ColectionException Pokud se nepovede naimportovat data.
     * @return Colection
     */
    public function importColection(array $record)
    {
        if (empty($this->colectionClass)) {
            throw new ColectionIAException('Ve tride ' . $this->getClass() . ' neni vyplnena vlastnost $colectionClass. Nemuzu naimportovat data.');
        }

        try {
            foreach ($record as $key => $value) {
                $this->colection[$key] = new $this->colectionClass;
                $this->colection[$key]->importRecord($value);
            }
        } catch (DataObjectIAException $e) {
            throw new ColectionException('Ve tride ' . $this->getClass() . ' nastala chyba: ' . $e->getMessage());
        }

        return $this;
    }

    /**
     * Vrati pocet polozek vlastnosti colection.
     *
     * @return integer
     */
    public function count()
    {
        return count($this->colection);
    }

    /**
     * Previne iterator na zacatek seznamu a nastavi vlastnost index na 0.
     */
    public function rewind()
    {
        reset($this->colection);
        $this->index = 0;
    }

    /**
     * Vrati hodnotu na aktualni pozici.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->colection);
    }

    /**
     * Vrati klic aktualni pozice.
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->colection);
    }

    /**
     * Presune iterator na dalsi dvojci klic/hodnota.
     */
    public function next()
    {
        next($this->colection);
        $this->index++;
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
     * Vrati hodnotu vlastnosti colection.
     *
     * @return array
     */
    public function getColection()
    {
        return $this->colection;
    }

    /**
     * Nastavi hodnotu vlastnosti colection.
     *
     * @param array $colection
     * @return Colection
     */
    public function setColection(array $colection)
    {
        $this->colection = $colection;
        return $this;
    }

    /**
     * Vrati hodnotu vlastnosti colectionClass.
     *
     * @return string
     */
    public function getColectionClass()
    {
        return $this->colectionClass;
    }

    /**
     * Nastavi hodnotu vlastnosti colectionClass.
     *
     * @param string $colectionClass
     * @return Colection
     */
    public function setColectionClass($colectionClass)
    {
        $this->colectionClass = (string)$colectionClass;
        return $this;
    }

    /**
     * Nastavi hodnotu vlatnosti index.
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Nastavi hodnotu vlastnosti index.
     *
     * @param integer $intex
     * @return Colection
     */
    public function setIndex($intex)
    {
        $this->index = (integer)$index;
        return $this;
    }

    /**
     * Zjistuje jestli existuje klic v poli.
     *
     * @param string $name
     * @return boolean
     */
    public function offsetExists($name)
	{
		return isset($this->colection[$name]);
	}

	/**
     * Vrati hodnotu na zaklade klice.
     *
     * @param string $name
     * @return mixed
     */
    public function offsetGet($name)
	{
        return $this->colection[$name];
	}

	/**
     * Tato metoda neni a nebude implementovana.
     * Zde je jen z duvodu rozhrani.
     */
    public function offsetSet($name, $value)
	{
		//$this->colection[$name] = $value;
        throw new ColectionException('Metoda offsetSet neni a nebude implementovana.');
	}

	public function offsetUnset( $name )
	{
		//unset($this->colection[$name]);
        throw new ColectionException('Metoda offsetUnset neni a nebude implementovana.');
	}
}