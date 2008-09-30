<?php
class Api
{
	public static function errorMessage( $text )
	{
		
		$output	= "\n<div class=\"error-message\">\n";
		$output	.= "<img src=\"/share/icons/default/actions/error.png\" class=\"img-tips\" />\n";
		$output	.= "<div class=\"pad\">\n" . $text ." </div>\n";
		$output	.= "\n</div>\n";
		
		return $output;
	}
	
	public static function tipMessage( $text )
	{
		$output	= "\n<div class=\"tip-message\">\n";
		$output	.= "<img src=\"themes/admin/images/icon/question.png\" class=\"img-tips\" />\n";
		$output	.= "<div class=\"pad\">\n".$text."</div><!--/pad-->\n";
		$output	.= "\n</div><!--/yellow_tips-->\n";
		
		return $output;
	}
	
	/**
	 * Tento druh hlasek se vypisuje jen uzivatelu, kteri na ne maji pravo
	 * @todo dodelat checkovani prav
	 * @return 
	 * @param $text Object
	 */
	public static function fatalMessage( $text )
	{
		$output	= "\n<div class=\"fatal-message\">\n";
		$output	.= "<img src=\"/share/icons/default/actions/alarm.png\" class=\"img-tips\" />\n";
		$output	.= "<div class=\"pad\">\n" . $text ." </div>\n";
		$output	.= "\n</div>\n";
		
		return $output;
	}
	
	/**
	 * Odstranuje tagy a prevadi specialni znaky na html entity
	 * @return $value ocistena
	 * @param $value string, array
	 */
	public static function handleTrash( $value ) 
	{
		if(is_array( $value )) {
			foreach($value as $key => $data) {
				if(is_array( $data )) {
					self::handleTrash($data);
				} else {
					$value[$key] = htmlspecialchars(strip_tags( $data ));
				}
			}
		} else {
			$value = htmlspecialchars(strip_tags( $value ));
		}
		return $value;	
	}
	

	/**
	 * Vyrizne ze script name nazev
	 *
	 * @return string $matches[1] vrati nazev scriptu oriznute o php
	 */
	public static function getProcessMethod()
	{
		preg_match( "/(\w*)\.php/", $_SERVER['SCRIPT_NAME'], $matches );
		if( isset ( $matches[1] ) ){
			return $matches[1];	
		}else {
			throw new KernelException("Nelze identifikovat spoustejici script.");
		}
		
	}
	
	/**
	 * Prevede pole commandu na hash, podle ktereho se pak vyhledava
	 *
	 * @param array $command
	 * @return string | boolean
	 */
	public static function commandToHash( $command ) 
	{
		return ( is_array( $command ) ) ? md5( implode( '/', $command ) ) : FALSE;
	}
	
	
	/**
	 * Pro lepsejsi query do DB je tu tato funkce
	 */
	public static function bobrTime($time)
	{
	  $mydate = date("d/m/Y - H:i", $time);
	  return $mydate;
	}
	
	/**
	 * Nahradi nevyzadane znaky za normalni pismenka.
	 *
	 * @param string $str
	 * @return string
	 */
	
	public function coolUri($str){
     	
	    // pole znaku ktere se maji nahradit
	     $replace = array(
	    'À'=>'A','Á'=>'A','Â'=>'A','Ã'=>'A','Ä'=>'A','Å'=>'A','Ā'=>'A','Ą'=>'A','Ă'=>'A',
	    'à'=>'a','á'=>'a','â'=>'a','ã'=>'a','ä'=>'a','å'=>'a','ā'=>'a','ą'=>'a','ă'=>'a',
	    'Æ'=>'Ae',
	    'æ'=>'ae',
	    'Ç'=>'C','Ć'=>'C','Č'=>'C','Ĉ'=>'C','Ċ'=>'C',
	    'ç'=>'c','ć'=>'c','č'=>'c','ĉ'=>'c','ċ'=>'c',
	    'Ď'=>'D','Đ'=>'D','Ð'=>'D',
	    'ď'=>'d','đ'=>'d','ð'=>'d',
	    'È'=>'E','É'=>'E','Ê'=>'E','Ë'=>'E','Ē'=>'E','Ę'=>'E','Ě'=>'E','Ĕ'=>'E','Ė'=>'E',
	    'è'=>'e','é'=>'e','ê'=>'e','ë'=>'e','ē'=>'e','ę'=>'e','ě'=>'e','ĕ'=>'e','ė'=>'e',
	    'ƒ'=>'f',
	    'Ĝ'=>'G','Ğ'=>'G','Ġ'=>'G','Ģ'=>'G',
	    'ĝ'=>'g','ğ'=>'g','ġ'=>'g','ģ'=>'g',
	    'Ĥ'=>'H','Ħ'=>'H',
	    'ĥ'=>'h','ħ'=>'h',
	    'Ì'=>'I','Í'=>'I','Î'=>'I','Ï'=>'I','Ī'=>'I','Ĩ'=>'I','Ĭ'=>'I','Į'=>'I','İ'=>'I',
	    'ì'=>'i','í'=>'i','î'=>'i','ï'=>'i','ī'=>'i','ĩ'=>'i','ĭ'=>'i','į'=>'i','ı'=>'i',
	    'Ĳ'=>'Ij',
	    'ĳ'=>'ij',
	    'Ĵ'=>'J',
	    'ĵ'=>'j',
	    'Ķ'=>'K',
	    'ķ'=>'k','ĸ'=>'k',
	    'Ł'=>'L','Ľ'=>'L','Ĺ'=>'L','Ļ'=>'L','Ŀ'=>'L',
	    'ł'=>'l','ľ'=>'l','ĺ'=>'l','ļ'=>'l','ŀ'=>'l',
	    'Ñ'=>'N','Ń'=>'N','Ň'=>'N','Ņ'=>'N','Ŋ'=>'N',
	    'ñ'=>'n','ń'=>'n','ň'=>'n','ņ'=>'n','ŉ'=>'n','ŋ'=>'n',
	    'Ò'=>'O','Ó'=>'O','Ô'=>'O','Õ'=>'O','Ö'=>'O','Ø'=>'O','Ō'=>'O','Ő'=>'O','Ŏ'=>'O',
	    'ò'=>'o','ó'=>'o','ô'=>'o','õ'=>'o','ö'=>'o','ø'=>'o','ō'=>'o','ő'=>'o','ŏ'=>'o',
	    'Œ'=>'Oe',
	    'œ'=>'oe',
	    'Ŕ'=>'R','Ř'=>'R','Ŗ'=>'R',
	    'ŕ'=>'r','ř'=>'r','ŗ'=>'r',
	    'Ś'=>'S','Š'=>'S','Ş'=>'S','Ŝ'=>'S','Ș'=>'S',
		  'š'=>'s',
	    'Ť'=>'T','Ţ'=>'T','Ŧ'=>'T','Ț'=>'T','Þ'=>'T',
	    'þ'=>'t','ť'=>'t',
	    'Ù'=>'U','Ú'=>'U','Û'=>'U','Ü'=>'U','Ū'=>'U','Ů'=>'U','Ű'=>'U','Ŭ'=>'U','Ũ'=>'U','Ų'=>'U',
	    'ú'=>'u','û'=>'u','ü'=>'u','ū'=>'u','ů'=>'u','ű'=>'u','ŭ'=>'u','ũ'=>'u','ų'=>'u',
	    'Ŵ'=>'W',
	    'ŵ'=>'w',
	    'Ý'=>'Y','Ŷ'=>'Y','Ÿ'=>'Y','Y'=>'Y',
	    'ý'=>'y','ÿ'=>'y','ŷ'=>'y',
	    'Ź'=>'Z','Ž'=>'Z','Ż'=>'Z',
	    'ž'=>'z','ż'=>'z','ź'=>'z',
	    'ß'=>'ss','ſ'=>'ss');
	    
	    // nahradi znaky s diakritikou na znaky bez diakritiky 
	    $str = strtr($str, $replace);
	    
	    // prevede vsechna velka pismena na mala 
	    $str = strtolower ($str);  
	
	    // nahradi pomlckou vsechny znanky, ktera nejsou pismena 
	    $re = "/[^[:alpha:][:digit:]]/"; 
	    $replacement = "-"; 
	    $str = preg_replace ($re, $replacement, $str); 
	
	    // odstrani ze zacatku a z konce retezce pomlcky 
	    $str = trim ($str, "-"); 
	
	    // odstrani z adresy pomlcky, pokud jsou dve a vice vedle sebe 
	    $re = "/[-]+/"; 
	    $replacement = "-"; 
	    $str = preg_replace ($re, $replacement, $str); 

    return $str;  
  }
  
}