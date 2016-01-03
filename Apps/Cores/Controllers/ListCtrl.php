<?php

namespace Apps\Cores\Controllers;

class ListCtrl extends CoresCtrl
{

    function index()
    {
        $this->twoColsLayout->render('List/list.phtml');
    }

}
