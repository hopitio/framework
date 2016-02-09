<?php

namespace Apps\Cores\Controllers;

use Libs\Controller;

class HomeCtrl extends CoresCtrl
{

    function configJS()
    {
        $config = json_encode(array(
            'siteUrl' => url(),
            'appName' => \Config::APP_NAME
        ));

        $this->resp->setBody("var CONFIG = $config;");
    }

}
