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
        $this->requireAdmin();
        $this->twoColsLayout->render('User/user.phtml');
    }

    function group()
    {
        $this->requireAdmin();
        $this->twoColsLayout->render('User/group.phtml');
    }

    function test()
    {
        $this->defaultLayout = new \Apps\Cores\Views\Layouts\DefaultLayout($this->context);
        $this->defaultLayout->setTemplatesDirectory(dirname(__DIR__) . '/Views');
        $this->defaultLayout->render('/test.phtml');
    }

}
