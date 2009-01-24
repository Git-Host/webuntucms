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
        // Zakladni nastaveni pro sablonu
        // @var string
        'WEBTITLE' => 'Bobroid',
        // Pokud ma byt vkladano nejaky defaultni cascadovy styl a neni to styl,
        // ktery je zapsan u PageId, muze se vkladat zde.
        // @var string
        'DEFAULTCSS' => array('themes/default/css/console.css'),
        'METATAGS' => array(
            array('http-equiv' => 'content-type', 'content' => 'text/html; charset=UTF-8'),
            array('name' => 'description', 'content' => 'BOBR v 2.0 cr. 3'),
            array('name' => 'copyright', 'content' => 'Copyright (C) BOBR o.s. Konekuto'),
            array('name' => 'author', 'content' => 'RBAS, o.s. Konekuto')
        )
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