<?php
class Menu_Module
{
	public static $className = __CLASS__;
	public static $instance = FALSE;
	public static $value;
	public $blockList;
	public $output;


	public function __construct( $command = NULL ) {
		$method = $command[1].'Web';
		echo $method;
		return $this->$method();
	}

	public function editWeb() {


		//otestovani defaultnich hodnot anonymouse
		if( 	FALSE		===	$_SESSION['user']['logged']
			&&	"anonymous" === $_SESSION['user']['nick']
			&&	0			=== $_SESSION['user']['privilege']  )
		{
			$form =
					'<div id="login">
						<form action="" method="post" name="loginForm">
							<label>Login</label><input type="text" id="login" name="login" value="user" />
							<label>Password</label><input type="password" id="pass" name="pass" value="pass" />
							<input type="submit" id="submitLogin" class="button login" value="Login"/>
						</form>
					</div>
					<a href="/">Odkaz na root webu</a>
					';

			$this->output .= $form;
		}
		// otestovani zalogovaneho uzivatele
		elseif( FALSE			!=	$_SESSION['user']['logged']
				&&	"anonymous" != 	$_SESSION['user']['nick']
				&&	0			< 	count($_SESSION['user']['groups'])  )
		{
				$form =
						'<div id="logout">
							<form action="/" method="post" name="logoutForm">
								<input type="hidden" name="logout" />
								<input type="submit" id="submitLogout" class="button logout" value="Logout" />
							</form>
						</div>
						<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
						';


				$this->output .= $form;
		}

		return $this->output;

	}

	public static function showAdmin() {

		$HTML = PageHtml::getInstance();

		//otestovani defaultnich hodnot anonymouse
		if( 	FALSE		===	$_SESSION['user']['logged']
			&&	"anonymous" === $_SESSION['user']['nick']
			&&	0			=== $_SESSION['user']['privilege']  )
		{

			$form =
					'<div id="login">
						<form action="" method="post" name="loginForm">
							<label>Login</label><input type="text" id="login" name="login" value="user" />
							<label>Password</label><input type="password" id="pass" name="pass" value="pass" />
							<input type="submit" id="submitLogin" class="button login" value="Login"/>
						</form>
					</div>
					<a href="/">Odkaz na root webu</a>
					';

			$HTML->addOutput( $form );
		}
		// otestovani zalogovaneho uzivatele
		elseif( FALSE			!=	$_SESSION['user']['logged']
				&&	"anonymous" != 	$_SESSION['user']['nick']
				&&	0			< 	count($_SESSION['user']['groups'])  )
		{
				$form =
						'<div id="logout">
							<form action="/" method="post" name="logoutForm">
								<input type="hidden" name="logout" />
								<input type="submit" id="submitLogout" class="button logout" value="Logout" />
							</form>
						</div>
						<a href="/">Odkaz na root webu</a> | <a href="/bobradmin">Administrace</a>
						';

				$HTML->addOutput( $form );
		}

	}

	public static function setValue( $value )
	{
		self::$value = $value;
	}

	public function setBlockList( $blockList )
	{
		$this->blockList = $blockList;
	}

	public function getHtmlOutput()
	{
		$output = $this->output;
		//$this->output = '';
		return $output;
	}
}
?>