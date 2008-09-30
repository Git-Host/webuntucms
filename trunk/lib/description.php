<?php
class Description extends Object
{
	
	private static	$descriptionList;
	private static	$lang;
	private static	$cache,
					$cacheStorage = 'data/';
	
	
	
	public static function loadByIds( $descriptionIds )
	{
		if( NULL == self::$lang ){
			throw new DescriptionException('Nepodarilo se lokalizovat nektere popisky.');
		}else {
			$sql = "SELECT id, title, description 
					FROM " . BobrConf::DB_PREFIX . "description_" . self::$lang . "
					WHERE id IN ( " . $descriptionIds . ")";
			$description = self::$cache->sqlData( $sql, 'id' );
			if ( is_array( self::$descriptionList ) ){
				/**
				 * Array merge zde nejde pouzit protoze pri slucovani poli 
				 * prepise ciselny index coz je nezadouci
				 * 
				 * Poznamka:
				 * Pri slucovani pole pomoci znamenka + 
				 * Kdyz existuje pole se stejnym indexem zachovaji se starsi data
				 */
				//$this->description = array_merge( $this->description, $description );
				self::$descriptionList = self::$descriptionList + $description ;
			}else {
				self::$descriptionList = $description;
			}
			
			return self::$descriptionList;
		}
	}
	
	public static function getDescription( $id )
	{
		if( array_key_exists( $id, self::$descriptionList ) ){
			return self::$descriptionList[ $id ];
		}else{
			return NULL;
		}
	}
	public static function getDescriptionList()
	{
		return self::$descriptionList;
	}
	public static function setDescriptionList( $descriptionIds )
	{
		return self::loadByIds( $descriptionIds );
	}
	
	public static function getLang()
	{
		return self::$lang;
	}
	public static function setLang( $lang )
	{
		self::$cache = new Cache( self::$cacheStorage );
		return self::$lang = $lang;
	}
}