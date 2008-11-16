<?php
/**
 * Ladici staticke metody
 */
class Ladenka
{
	/** @var bool  use HTML tags in error messages and dump output? */
	public static $html; // PHP_SAPI !== 'cli'

	/** @var int  Debug::dump() - how many nested levels of array/object properties display Debug::dump()? */
	public static $maxDepth = 3;

	/** @var int  Debug::dump() - how long strings display Debug::dump()? */
	public static $maxLen = 150;

	/** @var bool @see Debug::enable() */
	private static $enabled = FALSE;

	/** @var string  name of the file where script errors should be logged */
	private static $logFile;

	/** @var resource */
	private static $logHandle;

	/** @var bool  send e-mail notifications of errors? */
	private static $sendEmails;

	/** @var string  e-mail headers & body */
	private static $emailHeaders = array(
		'To' => '',
		'From' => 'noreply@%host%',
		'X-Mailer' => 'Nette Framework',
		'Subject' => 'PHP: An error occurred on the server %host%',
		'Body' => '[%date%]',
	);

	/** @var callback */
	public static $mailer = array(__CLASS__, 'sendEmail');

	/** @var float  probability that logfile will be checked */
	public static $emailProbability = 0.01;

	/** @var array  */
	public static $keysToHide = array('password', 'passwd', 'pass', 'pwd', 'creditcard', 'credit card', 'cc', 'pin');

	/** @var array  */
	private static $colophons = array(array(__CLASS__, 'getDefaultColophons'));

	/** @var array  */
	private static $keyFilter = array();

	/** @var int */
	public static $time;
	public static function print_re( $text )
	{
		echo "<pre style=\"text-align: left; font-size: 1em;\">";
			print_r( $text );
		echo "</pre>";
	}

	public static function var_dumper ( $text )
	{
		echo "<pre style=\"text-align: left; font-size: 1em;\">";
			 var_dump( $text );
		echo "</pre>";
	}

	/**
	 * Usmrti bez scriptu a vypise vstup
	 * @param mixed
	 * @return void
	 */
	public static function kill ( $mixed )
	{
		die( Ladenka::var_dumper( $mixed ) );
	}

	/**
	 * Dumps information about a variable in readable format.
	 *
	 * @param  mixed  variable to dump.
	 * @param  bool   return output instead of printing it?
	 * @return string
	 */
	public static function dump($var, $return = FALSE)
	{
		$output = "<pre class=\"dump\" style=\"text-align: left; font-size: 1em;\">" . self::_dump($var, 0) . "</pre>\n";

		if (!self::$html) {
			$output = htmlspecialchars_decode(strip_tags($output), ENT_NOQUOTES);
		}

		if (!$return) echo $output;

		return $output;
	}



	private static function _dump(&$var, $level)
	{
		if (is_bool($var)) {
			return "<span>bool</span>(" . ($var ? 'TRUE' : 'FALSE') . ")\n";

		} elseif ($var === NULL) {
			return "<span>NULL</span>\n";

		} elseif (is_int($var)) {
			return "<span>int</span>($var)\n";

		} elseif (is_float($var)) {
			return "<span>float</span>($var)\n";

		} elseif (is_string($var)) {
			if (strlen($var) > self::$maxLen) {
				$s = htmlSpecialChars(substr($var, 0, self::$maxLen), ENT_NOQUOTES) . ' ... ';
			} else {
				$s = htmlSpecialChars($var, ENT_NOQUOTES);
			}
			return "<span>string</span>(" . strlen($var) . ") \"$s\"\n";

		} elseif (is_array($var)) {
			$s = "<span>array</span>(" . count($var) . ") {\n";
			$space = str_repeat('  ', $level);

			static $marker;
			if ($marker === NULL) $marker = uniqid("\x00", TRUE);
			if (isset($var[$marker])) {
				$s .= "$space  *RECURSION*\n";

			} elseif ($level < self::$maxDepth || !self::$maxDepth) {
				$var[$marker] = 0;
				foreach ($var as $k => &$v) {
					if ($k === $marker) continue;
					$s .= "$space  " . (is_int($k) ? $k : "\"$k\"") . " => ";
					if (self::$keyFilter && is_string($v) && isset(self::$keyFilter[strtolower($k)])) {
						$s .= "<span>string</span>(?) <i>*** hidden ***</i>\n";
					} else {
						$s .= self::_dump($v, $level + 1);
					}
				}
				unset($var[$marker]);
			} else {
				$s .= "$space  ...\n";
			}
			return $s . "$space}\n";

		} elseif (is_object($var)) {
			$arr = (array) $var;
			$s = "<span>object</span>(" . get_class($var) . ") (" . count($arr) . ") {\n";
			$space = str_repeat('  ', $level);

			static $list = array();
			if (in_array($var, $list, TRUE)) {
				$s .= "$space  *RECURSION*\n";

			} elseif ($level < self::$maxDepth || !self::$maxDepth) {
				$list[] = $var;
				foreach ($arr as $k => &$v) {
					$m = '';
					if ($k[0] === "\x00") {
						$m = $k[1] === '*' ? ' <span>protected</span>' : ' <span>private</span>';
						$k = substr($k, strrpos($k, "\x00") + 1);
					}
					$s .= "$space  \"$k\"$m => ";
					if (self::$keyFilter && is_string($v) && isset(self::$keyFilter[strtolower($k)])) {
						$s .= "<span>string</span>(?) <i>*** hidden ***</i>\n";
					} else {
						$s .= self::_dump($v, $level + 1);
					}
				}
				array_pop($list);
			} else {
				$s .= "$space  ...\n";
			}
			return $s . "$space}\n";

		} elseif (is_resource($var)) {
			return "<span>resource of type</span>(" . get_resource_type($var) . ")\n";
		}
	}



	/**
	 * Starts/stops stopwatch.
	 * @return elapsed seconds
	 */
	public static function timer()
	{
		static $time = 0;
		$now = microtime(TRUE);
		$delta = $now - $time;
		$time = $now;
		return $delta;
	}

	public static function bobrErrorHandler($errorNum, $errorMessage, $errorFile, $errorLine)
	{
	    switch ($errorNum) {
	    case E_USER_ERROR:
	        echo '<div style="padding: 10px; background: #b63737; color: #ecd795;border: 3px solid #ecd795;">';
			echo "<b>ERROR</b> [$errorNum] $errorMessage<br />\n";
	        echo "  Fatal error on line $errorLine in file $errorFile";
	        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")\n";
	        echo "Aborting...<br />\n";
			echo "</div>\n";
			exit(1);
	        break;

	    case E_USER_WARNING:
	        echo '<div style="padding: 10px; background: #b63737; color: #ecd795;border: 3px solid #ecd795;">';
			echo "<b>WARNING</b> [$errorNum] $errorMessage\n";
			echo "</div>\n";
	        break;

	    case E_USER_NOTICE:
	        echo '<div style="padding: 10px; background: #b63737; color: #ecd795;border: 3px solid #ecd795;">';
			echo "<b>NOTICE</b>: [$errorNum] $errorMessage\n";
			echo "</div>\n";
	        break;

	    default:
	        echo '<div style="padding: 10px; background: #b63737; color: #ecd795;border: 3px solid #ecd795;">';
			echo "\nUnknown error type: [$errorNum] $errorMessage\n";
			echo "</div>\n";
	        break;
	    }

	    /* Don't execute PHP internal error handler */
	    return true;
	}

	// pokud nekde bude neosetrena vyjimka vypisem vse co o ni budem vedet
	// @todo lokalizovat text napriklad pomoci getTextu....
	public static function exceptionHandler( Exception $exception )
	{
		$trace = $exception->getTrace();
		// vytvorime si stranku
		$HTML = PageHtml::getInstance();

		$HTML->addWebTitle( $exception->getMessage() );
		$HTML->addCss('/share/other/exception.css');
		$HTML->addScript('<script type="text/javascript" src="/share/javascript/jquery/jquery.pack.js"></script>');
		// schovame trace
		$script = '<script type="text/javascript">$(document).ready(function(){var status;$("pre").hide(function(){$("button").show();status = 0;});$("button").click(function(){if( status == 0 ){$("pre").show();status = 1;}else{$("pre").hide();status = 0;}});});</script>';

		$HTML->addScript( $script );

		$output = "<div id=\"title\">\n";
			$output .= "\n<h1>Nezachycená vyjímka typu " . get_class( $exception ) . "</h1>";
			$output	.= "<div class=\"message\">";
				$output .= $exception->getMessage();
			$output .= "</div>\n";
		$output .="</div>\n";

		$output .= "<div class=\"code\">Ze souboru: <b>" . $exception->getFile() . "</b> na řádce <b>" . $exception->getLine() . "</b> </div>\n";
		$output .= "<div class=\"code\">\n";
			$output .= "<p><h3>" . $trace[0]['class'] . $trace[0]['type'] . $trace[0]['function'] . '(' ;

			// projedem vsechny argumenty co se predavali do volane metody
			$arguments = '';
			foreach ( $trace[0]['args'] as $argument ){
				$arguments .= $argument . ',';
			}
			$arguments = iconv_substr( $arguments, 0, -1 );
			$output .= $arguments . ")</h3></p>\n";

			$output .= "<p>Vyjímka je volána v souboru: <b>" . $trace[0]['file'] . "</b> na řádce <b>" . $trace[0]['line'] . "</b></p>\n";
		$output .= "</div>\n";

		$HTML->addOutput( $output );

		$HTML->addOutput('<button style=\"display:none;\">Trace</button>');
		$HTML->getPage();

		Ladenka::print_re( $exception->getTrace());
	exit;
	}

	/**
	 * Nacte soubor $filename a zobrazi kod okolo radky $line
	 *
	 * @param string $filename Nazev souboru
	 * @param integer $line Cislo chyboveho radku
	 * @return stirng
	 */
	public static function loadErrorCode( $filename, $line )
	{

		// nacteme soubor
		$handle = fopen($filename, "r");
		$contents = fread($handle, filesize($filename));
		// explodnem text podle byleho znaku enteru na jednotlive radky
		$contents = explode("\n", $contents);
		fclose($handle);

		$output ="<div style='border: solid 3px #eeeeee;margin: 10px;'>";

			// dopocitame si okoli radku
			$lineFrom = $line > 3  ? $line - 3 : 1;

			if( count ($contents) <= $line +3 ){
				$lineTo = $line -1;
			}else{
				$lineTo = $line + 3;
			}

		// nastavime barvy
		//$color = array('#e8fffd', '#fff');
		$color = array('#c54b4b', '#b64545');
		$counter = 0;
		// projedem si potrebne radky
		for ($i = $lineFrom; $i <= $lineTo; $i++){

			if( $i == $line){
				$output .= "<div style='background:#ff771d;font-weight:bolder;color:#000;padding: 3px;'><span style='font-size:small;padding-right: 10px;'>$i:</span> " . htmlspecialchars($contents[$i]) . "</div>\n";
			}else{
				$counter++;
				$output .= "<div style='background:". $color[$counter] . ";'><span style='font-size:small;padding-right: 10px;'>$i:</span> " . htmlspecialchars($contents[$i]) . "&nbsp;</div>\n";
			}
			$counter == 1  ? $counter = -1 : NULL;


		}
		$output .= "</div>\n";
		return $output;
	}
}




