<?php

try {
    require_once __WEB_ROOT__ . '/Lib/functions.php';

    require_once __WEB_ROOT__ . '/Kernel/Config/AbstractConfig.php';

    require_once __WEB_ROOT__ . '/Kernel/Config/localconfig.php';

    require_once __WEB_ROOT__ . '/Kernel/BobrLoader.php';

    Kernel_BobrLoader::getInstance()->base = __WEB_ROOT__;
    Kernel_BobrLoader::getInstance()->register();

    $bobr = new Kernel_Bobr;
    $bobr->run();
} catch (Exception $e) {
    echo $e->getMessage();
}
