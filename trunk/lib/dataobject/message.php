<?php
/**
 * trida je singleton - jo? rikal kdo jako? spis by mela byt abstract, tj. knihovna
 * pres tuto tridu se posilaji zpravy uzivatelum
 * zprvy se ukladaji do SESSION a dokud se nevypisou a nebo nevyprsi SESSION tak tam zustanou
 */
class Message
{
	/**
	 * prida hlasku error
	 * @return
	 * @param $text string
	 */
	public static function addError($text)
	{
		$_SESSION['message']['error'][]	=	$text;
	}

	/**
	 * prida hlasku fatal error, tato hlaska se zobrazuje jen administratoru
	 * @return
	 * @param $text string
	 */
	public static function addFatal($text)
	{
		$_SESSION['message']['fatal'][]	=	$text;
	}


	/**
	 * prida tip
	 * @return
	 * @param $text string
	 */
	public static function addTip($text)
	{
		$_SESSION['message']['tip'][]	=	$text;
	}



	/**
	 * vypise hlasky a to bud konkretni nebo vsechny
	 * @return string / FALSE
	 * @param $type Object[optional]
	 */
	public function show($type = 'all')
	{
		if(isset($_SESSION['message'])) {
			$output = "";
			// zjistime jake pole se ma volat
			if('all' === $type) {
				$array = $_SESSION['message'];
			} elseif(array_key_exists($type, $_SESSION)) {
				$array	=	$_SESSION['message'][$type];
			} else {
				return FALSE;
			}

			foreach($array as $name => $content) {
				foreach($content as $value) {
					$methodName = $name.'Message';
					// zavolame konkretni metodu, ktera slouzi jako obalka pro stabni stylovani CSS hlasek
					$output.= $this->$methodName($value);
				}
				// odnastavime zpravy aby se nezobrazovali po dobu trvani session
				unset($_SESSION['message']);
			}
			return $output;
		} else {
			return FALSE;
		}
	}



	/**
	 * vypise error text obaleny ve stabne nastylovanem divu
	 * @return string
	 * @param $message string
	 */
	protected function errorMessage($message)
	{
		$output = Api::errorMessage($message);
		return $output;
	}



	/**
	 * vypise tip text obaleny ve stabne nastylovanem divu
	 * @return string
	 * @param $message string
	 */
	protected function tipMessage($message)
	{
		$output = Api::tipMessage($message);
		return $output;
	}

	/**
	 * vypise fatal hlasku pokud je v sesne privilegium vetsi nez 500 vcetne
	 * @return
	 * @param $message Object
	 */
	protected function fatalMessage($message)
	{
		//if($_SESSION['user']['prv'] > 499) {
			$output = Api::fatalMessage($message);
			return $output;
		//}
	}

}