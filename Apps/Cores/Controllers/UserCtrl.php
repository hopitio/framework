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
        $depPk = (int) $this->req->get('dep');

        $dep = $this->depMapper
                ->makeInstance()
                ->setLoad()
                ->filterPk($depPk)
                ->getEntity();

        $this->twoColsLayout
                ->setData(array(
                    'department' => $dep,
                    'params'     => $this->req->get()
                ))
                ->render('User/user.phtml');
    }

    function indexPost()
    {

        $this->index();
    }

}
