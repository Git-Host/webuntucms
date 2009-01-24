<?php
/**
 * Vytvoreny objekt z globalniho pole $_POST
 * 
 * @author rbas
 */
class Bobr_Request_HttpPost
{
    /**
     * Informace zda-li jiz byl POST nastaven.
     *
     * @var boolean
     */
    private static $isAssigned = FALSE;

	/**
     * Pole hodnot z POSTu
     *
     * @var array
     */
    private $post = array();

	/**
     * Nacte do sebe $_POST
     *
     * @param array $POST
     */
    public function __construct(array $POST)
	{
		if (FALSE === self::$isAssigned) {
			$this->post = $POST;
			self::$isAssigned = TRUE;
		} else {
			throw new Exception('$_POST jiz byl nacten.');
		}
	}

    /**
     * Vrati hodnotu vlastnosti post.
     *
     * @return array
     */
    public function getPost()
    {
        return $this->post;
    }
}