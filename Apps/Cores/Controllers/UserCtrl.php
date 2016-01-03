<?php

namespace Apps\Cores\Controllers;

class UserCtrl extends CoresCtrl
{

    function index()
    {
        $this->twoColsLayout->render('User/user.phtml');
    }

}
