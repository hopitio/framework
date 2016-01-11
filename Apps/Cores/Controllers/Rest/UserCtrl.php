<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\Json;
use Libs\RestCtrl;
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

    function getDepartment($depPk)
    {
        $depPk = (int) $depPk;
        $loadUsers = $this->req->get('users');
        $loadDeps = $this->req->get('departments');
        $rescusively = $this->req->get('rescusively');
        $loadAncestors = $this->req->get('ancestors');
        $not = $this->req->get('not');

        $dep = $this->depMapper
                ->makeInstance()
                //autoload related entities
                ->setLoadAncestors($loadAncestors)
                ->setLoadChildDeps($loadDeps, $rescusively)
                ->setLoadUsers($loadUsers)
                ->filterNot($not)
                //query
                ->filterPk($depPk)
                ->getEntity();

        $this->resp->setBody(Json::encode($dep));
    }

    function updateDepartment($depPk)
    {
        $code = $this->restInput('depCode');
        $name = $this->restInput('depName');
        $stt = $this->restInput('stt');
        $depFk = $this->restInput('depFk');

        $depPk = $this->depMapper->updateDep($depPk, $depFk, $code, $name, $stt);
        $this->resp->setBody(Json::encode(array(
                    'status'   => true,
                    'resource' => url('/rest/department/' . $depPk)
        )));
    }

}
