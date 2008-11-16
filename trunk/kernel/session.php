<?php
/**
 * Session slouzi jako
 * kolekce metod pro ovladani session
 * @todo Doresit logout redirect, a sifry
 * @todo Do SESSION sahat pres request a ne z globalniho pole $_SESSION
 * @return $this
 * @param User_Kernel Object
 */
final class Session extends Object
{
	private static 	$classname	= __CLASS__;
	private static	$instance  	= FALSE,
					$user 	  	= "anonymous",
					$logged	  	= FALSE,
					$privilege 	= 0,
					$privateKey	= 'sifra1',
					$publicKey	= 'sifra2',
					$salt	  	= 'trochaSoli', // private_KEY
					$addr	  	= FALSE;

	// Do teto promene se ukladaji data z globalu / prehodit na private
	private		$SESSION	=	array();

	public static function getInstance() {
		if(FALSE == self::$instance) {
			return self::$instance = new self::$classname;
		} else {
			return self::$instance;
		}
	}

	private function __construct()
	{
		session_start();
		if( TRUE === $this->validateSession() ) {

			$this->SESSION	=	empty($_SESSION) ?	NULL	:	$_SESSION;

		} else {

			$this->logoutSession();
			// @todo resit inteligentneji
			$config = BobrConf::getInstance();
			Request::redirect( $config['WEB_ROOT'] );

		}
		//session_commit();
	}

	/**
	 * Zvaliduje session
	 * @return BOOLEAN
	 */
	private function validateSession()
	{

		// nemam session, nastavim anonymous
		if ( FALSE === isset($_SESSION['user']) ){

			$_SESSION['user']['nick']		=	self::$user;
			$_SESSION['user']['hash']		=   sha1(sha1(self::$salt).sha1($_SERVER['REMOTE_ADDR'].sha1(self::$publicKey)));
			$_SESSION['user']['logged']		=	FALSE;
			$_SESSION['user']['groups']		=	'2';
			$_SESSION['user']['privilege']	=	0;

			$this->SESSION = $_SESSION;
			return TRUE;
		} else {
			//otestovani defaultnich hodnot anonymouse
			if( FALSE			===	$_SESSION['user']['logged']
				&&	"anonymous" === $_SESSION['user']['nick']
				&&	'2' 		=== $_SESSION['user']['groups'] )
			{
				if(sha1(sha1(self::$salt).sha1($_SERVER['REMOTE_ADDR'].sha1(self::$publicKey))) == $_SESSION['user']['hash']) {
					return TRUE;
				} else {
					return FALSE;
				}

			}
			// otestovani zalogovaneho uzivatele
			elseif( FALSE			!=	$_SESSION['user']['logged']
					&&	'anonymous' != $_SESSION['user']['nick'])
			{
				if(sha1(sha1(self::$salt).sha1($_SERVER['REMOTE_ADDR']).sha1(self::$privateKey)) == $_SESSION['user']['hash'])
				{
					return TRUE;
				}
				else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * Vraci vyzadanou hodnotu ze $_SESSION
	 * @todo doresit validaci dat - prava
	 * @return array
	 * @param $key,$value
	 */
	public function getSession( $name, $key )
	{
		return isset($this->SESSION[$name][$key]) ?  $this->SESSION[$name][$key] : FALSE;
	}

	/**
	 * Pridava klice do $_SESSION
	 * @todo doresit validaci vkladanych dat - prava
	 * @return TRUE
	 * @param $key,$value
	 */
	public function setSession( $key , $value )
	{
		$this->SESSION[$key] = $value;
		return TRUE;
	}

	/**
	 * Verejna funkce umozunujici prihlaseni
	 * @return boolean
	 * @param $nick,$pass
	 */
	public function tryLogin($nick,$pass)
	{
		return $this->loginSession(User::getInstance($nick,$pass,$this));
	}

	/**
	 * Pokusi se nastaavit uzivatele jako zalogovaneho v session
	 * @todo doresit dopisovani do session podle objektu User_Kernel
	 * @return boolean
	 * @param User_Kernel Object
	 */
	private function loginSession( User $userObj )
	{
			foreach( $_SESSION['user'] as $k => $v ) {
				$_SESSION['user'][$k] = '';
			}

			$_SESSION['user']['id']			= $userObj->id;
            $_SESSION['user']['nick']		= $userObj->nick;
            $_SESSION['user']['groups']		= $userObj->groups;
            $_SESSION['user']['units']		= $userObj->units;
			$_SESSION['user']['unitgroups']	= $userObj->unitgroups;
            $_SESSION['user']['hash']		= sha1(sha1(self::$salt).sha1($_SERVER['REMOTE_ADDR']).sha1(self::$privateKey));
            $_SESSION['user']['logged'] 	= TRUE;

			$this->SESSION['user'] = $_SESSION['user'];
			return TRUE;
	}


	/**
	 * Znici uzivatelskou session
	 * @todo doresit zapis do databaze pri odlogovani se a redirect na nezalogovanou stranku
	 * @return boolean
	 */
	public function logoutSession()
	{
		if(session_destroy()) {
			return TRUE;
		} else {
			unset($_SESSION);
			return TRUE;
		}
	}

}

?>