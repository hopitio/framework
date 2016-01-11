<?php

namespace Apps\Cores\Controllers;

use Libs\Controller;

class HomeCtrl extends CoresCtrl
{

    function index()
    {
        $this->twoColsLayout->render('Home/index.phtml');
    }

    function book()
    {
        echo 'test';
    }

    function configJS()
    {
        $config = json_encode(array(
            'siteUrl' => url(),
            'appName' => \Config::APP_NAME
        ));

        $this->resp->setBody("var CONFIG = $config;");
    }

}
