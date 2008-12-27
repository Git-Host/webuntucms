<?php

try {
    require_once __WEB_ROOT__ . '/lib/functions.php';

    require_once __WEB_ROOT__ . '/kernel/config/AbstractConfig.php';

    require_once __WEB_ROOT__ . '/kernel/config/localconfig.php';

    require_once __WEB_ROOT__ . '/kernel/BobrLoader.php';

    BobrLoader::getInstance()->base = __WEB_ROOT__;
    BobrLoader::getInstance()->register();

    $bobr = new Bobr;
    $bobr->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
