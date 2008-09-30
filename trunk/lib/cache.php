<?php
/**
 * Cache
 * 
 * Pri ukladani do Cache se vzdy vytvari instance
 * teto tridy a do konstruktoru se prida $storage ( string ). 
 * $storage je konkretni slozka dane instance.
 * Objekt si sam zjisti jaka je instance a nastavi "cache root" do konkretni instance.
 * $storage je napriklad 'data' nebo 'template'.
 * 
 * Pr.:
 * $cache =  new Cache('data');
 * Ulozeni serializovanych dat do cache:
 * $cache->save( 'f7fayfya0f07f87f6a987fa98d7fa8f6a', $data, TRUE );
 * 
 * Nacteni serializovanych dat:
 * $cache->load( 'f7fayfya0f07f87f6a987fa98d7fa8f6a', TRUE );
 * 
 * Znevalidneni cache:
 * $cache->invalid( 'f7fayfya0f07f87f6a987fa98d7fa8f6a' );
 *
 * @todo  Dodelat funkci vypnuti cele kese dle konfigu
 * @todo Naucit znevalidnovat chacehromadne
 */
class Cache extends  Object
{
			// cesta ke slozce cache	
	private $storage,
			// koncovka souboru
			$fileType = 'php',
			// nacte se z BobrConf bool
			$logCache,
			// pokud se ma logovat prace s cachi uklada se pole sem
			$fileCacheList = array();
	
	
	/*
	 * Do konstruktoru se pridava konkretni slozka do ktere se v ramci instance bude cachovat.
	 */
	public function __construct( $storage )
	{
		$this->storage = BobrConf::CACHE_ROOT . Api::getProcessMethod() . '/' . $storage;
		$config = BobrConf::getSingleton();
		$this->logCache = $config['LOGGING_CACHE'];
	}
	
	/**
	 * Ulozi data do cache
	 * 
	 * $fileName string - Nazev souboru
	 * $data mixed - Data ktere ukladame
	 * $serialize bool - ukladat serializovane bo ne, default je FALSE
	 */
	public function save( $fileName, $data, $serialize = FALSE )
	{
		$fileName = $this->fileName( $fileName, 'save' );
		$file = fopen( $fileName, 'w+');
		if ( $file ){
			$data = $serialize ? serialize( $data ) : $data;
			fwrite( $file, $data );
			return fclose( $file );
		}else {
			throw new CacheException( 'Nepodarilo se vytvorit ' . $fileName . ' pro zapsani do cache.');
		}
	}
	
	/**
	 * Nacte data z cache
	 * 
	 * $fileName string - nazev souboru
	 * $unserialize bool - nacist deserializovane
	 */
	public function load( $fileName, $unserialize = FALSE )
	{
		$fileName = $this->fileName( $fileName, 'load' );
		if( TRUE === file_exists( $fileName ) ){
			$file = fopen( $fileName, 'r');
			$contents = fread ( $file , filesize ($fileName) );
			fclose ( $file );
			$contents = $unserialize ? unserialize( $contents ) : $contents;
			return $contents;
		}else {
			return FALSE;
		}
	}
	
	/**
	 * Vrati data v asspociatyvnim poli. Pokud nejsou v cachi nacte si je.
	 * 
	 * $sql string - SQL prikaz, ktery se nacachuje
	 * $key string - pokud ma byt pole nejak serazene
	 */
	public function sqlData( $sql, $key = NULL )
	{
		$cacheName = md5( $sql );
		$data = $this->load( $cacheName, TRUE );
		if( FALSE === $data ){
			$result = dibi::query( $sql );
			$result = $result->fetchAssoc( $key );
			$this->save( $cacheName, $result, TRUE );
			
			return $result;
		}else{
			return $data;
		}
	}
	
	/**
	 * Pokud je v cachi $caheName tak ho loadne
	 * pokud ne loadne ho z databaze, pokud je zadan $key seradi vysledek pole dle nej
	 * 
	 * @param $cacheName string - jemeno kesovanych dat
	 * @param $sql string - SQL prikaz, ktery se provede v pripade nepritomnosti dat v cachi
	 * @param $key string - klice v poli dle kterych se bude radit vysledek
	 * @return $data array - vrati vysledne pole z cache / databaze
	 */
	public function loadData( $cacheName, $sql, $key = NULL )
	{
		$data = $this->load( $cacheName, TRUE );
		if( FALSE === $data ){
			$result = dibi::query( $sql );
			$result = $result->fetchAssoc( $key );
			$this->save( $cacheName, $result, TRUE );
			
			return $result;
		}else{
			return $data;
		}
	}
	
	/**
	 * Znevalidni cachovi soubor
	 * 
	 * $fileName string - Nazev souboru
	 */
	public function invalid( $fileName )
	{
		$fileName = $this->fileName( $fileName, 'invalid' );
		return unlink( $fileName );
	}
	
	/**
	 * Vytvori nazev souboru a pripoji ktomu i cestu do storage.
	 * Pokud je zapnute logovani cache prida do pole vystupni jmeno.
	 * 
	 * $fileName string - jmeno souboru
	 * $type bool / string - string slouzi jako klic v poli pro logovani 
	 */
	private function fileName ( $fileName, $type =  FALSE )
	{
		$fileSource =  $this->storage . $fileName . '.' . $this->fileType;
		if( $type && TRUE === $this->logCache ){
			$this->fileCacheList[$type][] = $fileSource;
		}
		return $fileSource;
	}
	
	/**
	 * @todo dodelat logovaci tridu
	 */
	public function __destruct()
	{
		if( TRUE === $this->logCache ){
			// predelat log aby tydo data uklada do souboru
			Log::addCacheFile( $this->fileCacheList );
		}
	}
}