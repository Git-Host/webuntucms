<?php

class Database extends Object
{
	/**
	 * Vytvori spojeni na postgresql databazi
	 *
	 * @param string $connectionName nazev spojeni
	 */
	public static function connectionToPsqlDatabase( $connectionName )
	{
		// Zaregistrujeme spojeni na databazi
		$connect = dibi::connect(array(
			'driver'     => 'postgre',
			'string'     => 'host=' . BobrConf::DB_HOST . ' port=' . BobrConf::DB_PORT . ' dbname=' . BobrConf::DB_NAME . ' user=' . BobrConf::DB_USER . ' password=' . BobrConf::DB_PASSWORD . '',
			'persistent' => BobrConf::DB_PERSISTENT,
		), $connectionName );
		
		return $connect;
	}
}