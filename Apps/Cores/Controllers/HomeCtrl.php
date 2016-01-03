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

}
