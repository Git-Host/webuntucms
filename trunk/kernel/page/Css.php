<?php
/**
 * Obsahuje CSS cestu
 *
 * @author rbas
 */
class Css extends Object
{
	/**
     * Obsahuje URI ke kaskadam.
     *
     * @var string
     */
    private $css = '';

    /**
     * Natahne do sebe css cestu.
     *
     * @param string $css
     */
	public function __construct($css)
	{
		$this->css = $css;
	}

    public function getCss()
    {
        return $this->css;
    }
}