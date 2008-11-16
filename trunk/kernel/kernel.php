<?php
require_once __DIR__ . '/kernel/exceptions/exceptions.php';
require_once __DIR__ . '/kernel/bobrloader.php';

try{
	BobrLoader::getInstance()->base = __DIR__;
	BobrLoader::getInstance()->register();

	$bobr = new Bobr;
	$bobr->run();
	$_SESSION['prdelka'] = 'picundicka';

}catch ( KernelException $exception ){
	//@todo vykreslit neakou statickou stranku
	echo $exception->getMessage() .'<br />';
	echo $exception->getFile() .'<br />';
	echo $exception->getLine() .'<br />';
	echo "<pre>";
	var_dump( $exception->getTrace() );
	echo "</pre>";
}