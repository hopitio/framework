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

    function search()
    {
        $search = $this->req->get('search');
        $stt = $this->req->get('status', -1);

        $deps = $this->depMapper
                ->makeInstance()
                ->filterStatus($stt)
                ->filterDeleted(false)
                ->filterSearch($search)
                ->getAll();

        $users = $this->userMapper
                ->makeInstance()
                ->filterStatus($stt)
                ->filterDeleted(false)
                ->filterSearch($search)
                ->getAll();



        $this->resp->setBody(Json::encode(array(
                    'departments' => $deps,
                    'users'       => $users
        )));
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
        $stt = $this->req->get('status', -1);
        $groups = $this->groupMapper->makeInstance()
                ->filterStatus($stt)
                ->getAll();

        $this->resp->setBody(Json::encode($groups));
    }

    function getGroupUsers($groupPk)
    {
        $users = $this->userMapper
                ->makeInstance()
                ->select('dep.depName', false)
                ->filterDeleted(false)
                ->innerJoin('cores_group_user gu ON u.pk=gu.userFk AND gu.groupFk=' . intval($groupPk))
                ->leftJoin('cores_department dep ON u.depFk=dep.pk')
                ->getAll(function($rawData, $entity)
        {
            if (!$entity->depName)
            {
                $entity->depName = '[Thư mục gốc]';
            }
        });

        $this->resp->setBody(Json::encode($users));
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

        $this->resp->setBody(Json::encode(array(
                    'status'   => true,
                    'resource' => url('/rest/user/' . $id)
        )));
    }

    function deleteUsers()
    {
        $users = $this->restInput();
        $this->userMapper->deleteUsers($users);

        $this->resp->setBody(Json::encode(array(
                    'status' => true
        )));
    }

    function deleteDepartments()
    {
        $deps = $this->restInput();
        $this->depMapper->deleteDepartments($deps);

        $this->resp->setBody(Json::encode(array(
                    'status' => true
        )));
    }

    function moveUsers()
    {
        $users = $this->restInput('pks');
        $dest = $this->restInput('dest');

        $this->userMapper->moveUsers($users, $dest);
        $this->resp->setBody(Json::encode(array(
                    'status' => true
        )));
    }

    function moveDepartments()
    {
        $deps = $this->restInput('pks');
        $dest = $this->restInput('dest');

        $this->depMapper->moveDepartments($deps, $dest);
        $this->resp->setBody(Json::encode(array(
                    'status' => true
        )));
    }

    function updateGroup($pk)
    {
        $group = $this->restInput();
        $pk = $this->groupMapper->updateGroup($pk, $group);

        $this->resp->setBody(Json::encode(array(
            'pk' => $pk
        )));
    }

}
