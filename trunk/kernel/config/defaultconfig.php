<?php
/**
 * Defaultni config slouzi jako posledni misto k hledani konfiguratoru.
 * Zde mohou byt vsechny spolecne konfiguratory skrz vsechny web instance.
 * Nebo to muze slouzit jako posledni zachytnej bod pri hledani konfiguratoru.
 * Moznost vyuziti zalezi jen na fantasii a na narocnosti projektu.
 *
 * @author rbas
 */
class DefaultConfig implements ArrayAccess
{

    /**
     * Vycet konfiguratoru a jejich vlastnosti.
     *
     * @var array
     */
	private $settings = array(
		'KUBULA'	=>	'DefaultConfig',
		'EMANUEL'	=>	'Default Emanuel',
        // Zakladni nastaveni pro sablonu
        'WEBTITLE' => 'Bobroid',
	);

    /**
     * Zjisti jestli dany konfigurator existuje.
     *
     * @param string $name
     * @return boolean
     */
	public function offsetExists( $name )
	{
		$value = strtoupper( $name );
		return isset($this->settings[$name]);
	}

    /**
     * Vrati hodnotu konfiguratoru pokud existuje.
     *
     * @param <type> $name
     * @return mixed
     * @throws Exception
     */
	public function offsetGet( $name )
	{
		$value = strtoupper( $name );
		if(isset($this->settings[$value])){
			return $this->settings[ $value ];
		}else{
            throw new Exception("Vlastnost $name neexistuje v zadnem configuracnim souboru.");
		}
	}

    /**
     * Nastavi konfigurator.
     *
     * @param string $name
     * @param mixed $value
     */
	public function offsetSet( $name, $value )
	{
		$name = strtoupper( $name );
		$this->settings[$name] = $value;
	}

    /**
     * Odnastavi konfigurator.
     *
     * @param string $name
     */
	public function offsetUnset( $name )
	{
		unset($this->settings[$name]);
	}

    /**
     * Magicke volani vystupu metody offsetGet
     *
     * @param string $name
     * @return mixed
     */
	public function __get($name)
	{
		return $this->offsetGet($name);
	}
}