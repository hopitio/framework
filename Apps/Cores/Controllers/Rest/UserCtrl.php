<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\Json;
use Libs\RestCtrl;
use Apps\Cores\Models\DepartmentMapper;
use Apps\Cores\Models\UserMapper;
use Apps\Cores\Models\GroupMapper;
use Libs\Permission;

class UserCtrl extends RestCtrl
{

    protected $userMapper;
    protected $depMapper;
    protected $groupMapper;

    protected function init()
    {
        parent::init();
        $this->userMapper = UserMapper::makeInstance();
        $this->depMapper = DepartmentMapper::makeInstance();
        $this->groupMapper = GroupMapper::makeInstance();
    }

    function getDepartment($depPk)
    {
        $depPk = (int) $depPk;
        $loadUsers = $this->req->get('users');
        $loadDeps = $this->req->get('departments');
        $rescusively = $this->req->get('rescusively');
        $loadAncestors = $this->req->get('ancestors');
        $not = $this->req->get('not');

        $userMapper = $this->userMapper;

        $dep = $this->depMapper
                ->makeInstance()
                //autoload related entities
                ->setLoadAncestors($loadAncestors)
                ->setLoadChildDeps($loadDeps, $rescusively)
                ->setLoadUsers($loadUsers, function($rawData, $entity) use ($userMapper)
                {
                    $entity->groups = $userMapper->db->GetCol('SELECT groupFk FROM cores_group_user WHERE userFk=?', array($entity->pk));
                    $entity->permissions = $userMapper->loadPermissions($entity->pk);
                })
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

    function getGroups()
    {
        $groups = $this->groupMapper->makeInstance()
                ->filterStatus(true)
                ->getAll();

        $this->resp->setBody(Json::encode($groups));
    }

    function getBasePermissions()
    {
        $this->resp->setBody(Json::encode(Permission::getAll()));
    }

    function checkUniqueAccount()
    {
        $userPk = $this->restInput('pk');
        $acc = $this->restInput('account');
        $result = $this->userMapper->checkUniqueAccount($userPk, $acc);

        $this->resp->setBody(Json::encode($result));
    }

    function updateUser($id)
    {
        $data = $this->restInput();
        $id = $this->userMapper->updateUser($id, $data);
    }

}