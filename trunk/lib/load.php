<?php

try {
    require_once __WEB_ROOT__ . '/lib/functions.php';

    require_once __WEB_ROOT__ . '/kernel/config/AbstractConfig.php';

    require_once __WEB_ROOT__ . '/kernel/config/localconfig.php';

    require_once __WEB_ROOT__ . '/kernel/BobrLoader.php';

    Kernel_BobrLoader::getInstance()->base = __WEB_ROOT__;
    Kernel_BobrLoader::getInstance()->register();

    $bobr = new Kernel_Bobr;
    $bobr->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
