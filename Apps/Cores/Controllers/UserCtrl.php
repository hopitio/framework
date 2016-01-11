<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Models\UserMapper;
use Apps\Cores\Models\DepartmentMapper;

class UserCtrl extends CoresCtrl
{

    protected $userMapper;
    protected $depMapper;

    function init()
    {
        parent::init();
        $this->userMapper = UserMapper::makeInstance();
        $this->depMapper = DepartmentMapper::makeInstance();
    }

    function index()
    {
        $this->twoColsLayout->render('User/user.phtml');
    }

}
