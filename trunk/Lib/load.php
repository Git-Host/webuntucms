<?php

try {
    require_once __WEB_ROOT__ . '/Lib/functions.php';

    require_once __WEB_ROOT__ . '/Config/AbstractConfig.php';

    require_once __WEB_ROOT__ . '/Config/localconfig.php';

    require_once __WEB_ROOT__ . '/Bobr/BobrLoader.php';

    Bobr_BobrLoader::getInstance()->base = __WEB_ROOT__;
    Bobr_BobrLoader::getInstance()->register();

    $bobr = new Bobr_Bobr;
    $bobr->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
