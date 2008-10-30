<?php
class LocalConfig
{
	const DB_HOST				= 'localhost';
	const DB_PORT				= '2345';
	const DB_NAME				= 'bobrpico';
	const DB_USER				= 'bobr';
	const DB_PASSWORD			= 'rybasmrdi';
	const DB_PERSISTENT			= TRUE;
	const DB_CONNECTION_NAME	= 'default';
	const DB_PREFIX				= 'bobr_';

	const CACHE_ROOT			= '/local/cache/';
	const DEBUG_MODE			= TRUE;
	// Share url je zde mysleno ke slozce share. Vetsinou je url jen /
	const SHARE_URL				= '/';
}
?>