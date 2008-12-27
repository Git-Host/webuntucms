<?php

class AdminConfig extends AbstractConfig
{

	protected $settings = array(
		'CACHEMODE' => TRUE,
		'WEBROOT' => '/bobradmin/',
        'DEFAULTPAGEID' => 4,
        // Zakladni nastaveni pro sablonu
        'WEBTITLE' => 'Vitej v administraci BOBRoid 2.0 cr.3',
	);

}