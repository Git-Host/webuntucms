<?php


require_once __WEB_ROOT__ . '/lib/functions.php';

require_once __WEB_ROOT__ . '/kernel/config/localconfig.php';

require_once __WEB_ROOT__ . '/kernel/bobrloader.php';

BobrLoader::getInstance()->base = __WEB_ROOT__;
BobrLoader::getInstance()->register();

$bobr = new Bobr;
$bobr->run();