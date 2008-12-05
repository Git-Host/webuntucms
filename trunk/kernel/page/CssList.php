<?php
/**
 * Obsahuje pole Css objektu.
 *
 * @author rbas
 */
class CssList extends Object
{
	/**
     * Pole objektu Css.
     *
     * @var array
     */
    private $items = array();

	/**
     * Prida css link do sebe.
     *
     * @param string $cssLink 
     */
    public function __construct($cssLink)
	{
		$this->addCss($cssLink);
	}

    /**
     * Prida do pole items object Css.
     *
     * @param string $cssLink
     */
	public function addCss($cssLink)
	{
		$this->items[] = new Css($cssLink);
	}
}