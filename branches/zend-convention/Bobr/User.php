<?php
class Bobr_User extends Bobr_DataObject
{

	/**
	 * Identifikator uzivatele
	 *
	 * @var integer
	 */
	private $id = 0;

	/**
	 * Uzivatelske jmeno
	 *
	 * @var string
	 */
	private $nick = '';

	/**
	 * Email
	 *
	 * @var string
	 */
	private $email = '';

	/**
	 * Heslo
	 *
	 * @var string
	 */
	private $pass = '';

	/**
	 * Status uzivatele.
	 *
	 * @var integer
	 */
	private $statusId = 0;

	/**
	 * Id uzivatele ktery je anonymous.
	 *
	 * @var integer
	 */
	const ANONYMOUS_USER_ID = 2;

	public function __construct($id = 0)
	{
		$this->importProperties = array ('id' => 'id', 'nick' => 'nick', 'email' => 'email', 'pass' => 'pass', 'status_id' => 'statusId');
		if (0 != $id) {
			$this->setId($id);
			$this->load();
		}
	}

	/**
	 * Nacte data o uzivateli.
	 *
	 * @throws Bobr_UserIAException Pokud pri nacitani neni vyplnena vlastnos id.
	 * @return Bobr_User | NULL Null vraci pokud uzivatelske id neexistuje.
	 */
	public function load()
	{
		if (0 === $this->id) {
			throw new Bobr_UserIAException('Neni vyplneno id uzivatele, nemuzu nacist jeho data');
		}

		if (! $this->loadFromCache()) {
			$query = 'SELECT `id`, `nick`, `email`, `pass`, `status_id`
				FROM `' . Config::DB_PREFIX . 'users`
				WHERE `id` = ' . $this->id;
			$record = dibi::query($query)->fetch();
			if (empty($record)) {
				return NULL;
			}
			$this->importRecord($record)
				->saveToCache();
		}
	}

	/**
	 * Vrati kesove id
	 *
	 * @return string
	 */
	public function getCacheId()
	{
		return '/bobr/user/' . $this->getId() . '/' . $this->getClass();
	}

	/**
	 * Vrati hodnotu vlastnosti $id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Nastavi hodnotu vlastnosti $id
	 *
	 * @param integer
	 * @return Bobr_User
	 */
	public function setId($id)
	{
		$this->id = (integer)$id;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $nick
	 *
	 * @return string
	 */
	public function getNick()
	{
		return $this->nick;
	}

	/**
	 * Nastavi hodnotu vlastnosti $nick
	 *
	 * @param string
	 * @return Bobr_User
	 */
	public function setNick($nick)
	{
		$this->nick = (string)$nick;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $email
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Nastavi hodnotu vlastnosti $email
	 *
	 * @param string
	 * @return Bobr_User
	 */
	public function setEmail($email)
	{
		$this->email = (string)$email;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $pass
	 *
	 * @return string
	 */
	public function getPass()
	{
		return $this->pass;
	}

	/**
	 * Nastavi hodnotu vlastnosti $pass
	 *
	 * @param string
	 * @return Bobr_User
	 */
	public function setPass($pass)
	{
		$this->pass = (string)$pass;
		return $this;
	}

	/**
	 * Vrati hodnotu vlastnosti $statusId
	 *
	 * @return integer
	 */
	public function getStatusId()
	{
		return $this->statusId;
	}

	/**
	 * Nastavi hodnotu vlastnosti $statusId
	 *
	 * @param integer
	 * @return Bobr_User
	 */
	public function setStatusId($statusId)
	{
		$this->statusId = (integer)$statusId;
		return $this;
	}

}