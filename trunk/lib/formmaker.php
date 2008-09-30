<?php
/* using html.lib.php */
class FormMaker {
	/**
	 * Zaklada formulare - docas
	 *
	 * @param unknown_type $place
	 * @param unknown_type $action
	 * @param unknown_type $method
	 * @param unknown_type $enctype
	 * @return unknown
	 */
	public static function formOpen( $action, $method, $name, $attributes=NULL)
	{

		$action = mb_strtolower( $action );
		$method = mb_strtolower( $method );
		$name 	= mb_strtolower( $name );
		
			if( is_array( $attributes ) ) {
				$attr	= array();
				$attr	= array( 	'action'	=> $action ,
									'method'	=> $method ,
									'name'		=> $name 
								);
				$attr = array_merge($attr,$attributes);
				
			} else {
				$attr = '';
				$attr .='action="' . $action . '" method="'. $method . '" name="' . $name .'" ' . $attributes;
			}

		return HTML::tagSingle( 'form', 'a', $attr );
	}

	public static function formClose() {
		return HTML::tagSingle( 'form' );
	}
	
	/**
	 * Vytvari inputy do formularovych prvku
	 *
	 * @param string $type - typ formulare
	 * @param string $name - nazev formulare
	 * @param string $value - hodnota
	 * @param array $other - pole ostatnich atributu
	 * @return string $output
	 */
	public static function input($type, $name, $value, $other = NULL)
	{
		$output = '';
		$type = mb_strtolower( $type );
		$name = mb_strtolower( $name );
		
		$output	.= "<input type=\"" . $type . "\" name=\"" . $name . "\" ";
		if( is_array( $other ) ) {
			foreach ( $other as $attr => $v ) {
				$output .= $attr . '= "' . $v . '" ';
			}
		} elseif( is_string( $other ) ) {
			$output .= $other.' ';
		}	
		$output .= "value=\"" . $value . "\" />\n";
		return $output;
	}
	
	/**
	 * Vytvari options boxy - option, optgroups podle vycerozmernych poli
	 * @todo doladit generovani optionu , opt groups
	 * @param unknown_type $arr
	 * @param unknown_type $selected
	 * @return unknown
	 */
	public static function option( $arr, $selected = null, $groups = null ) {
      return FALSE;
    }
    
    /**
     * Vytvari select boxy
     *
     * @param unknown_type $name
     * @param unknown_type $other
     * @return unknown
     */
    public static function selectOpen( $arr, $attributes = NULL, $selected = null  ) {
    	return FALSE;
    }
    
    public static function selectClose() {
    	$output .= '</select>';
    }
    
}

?>