<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\Json;
use Apps\Cores\Models\DepartmentMapper;
use Apps\Cores\Models\UserMapper;

class UserCtrl extends RestCtrl
{

    protected $userMapper;
    protected $depMapper;

    protected function init()
    {
        parent::init();
        $this->userMapper = UserMapper::makeInstance();
        $this->depMapper = DepartmentMapper::makeInstance();
    }

    function get($depPk)
    {
        $loadUsers = $this->req->get('users');
        $loadDeps = $this->req->get('departments');
        $loadAncestors = $this->req->get('ancestors');

        $dep = $this->depMapper
                ->makeInstance()
                //autoload related entities
                ->setLoadAncestor($loadAncestors)
                ->setLoadChildDeps($loadDeps)
                ->setLoadUsers($loadUsers)
                //query
                ->filterPk($depPk)
                ->getEntity();
        
        $this->res->setBody(Json::encode($dep));
    }

}
