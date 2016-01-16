<?php

namespace Apps\Cores\Models;

use Libs\SQL\Mapper;

class UserMapper extends Mapper
{

    public function makeEntity($rawData)
    {
        return new UserEntity($rawData);
    }

    public function tableAlias()
    {
        return 'u';
    }

    public function tableName()
    {
        return 'cores_user';
    }

    function filterParent($depPk)
    {
        $this->where('u.depFk=?', __FUNCTION__)->setParam($depPk, __FUNCTION__);
        return $this;
    }

    function filterAccount($acc)
    {
        $this->where('u.account=?', __FUNCTION__)->setParam($acc, __FUNCTION__);
        return $this;
    }

    /** @return GroupEntity */
    function loadGroups($userPk)
    {
        return GroupMapper::makeInstance()
                        ->innerJoin('cores_group_user gu ON gu.groupFk=gp.pk AND gu.userFk=?' . intval($userPk))
                        ->getAll();
    }

    function loadPermissions($userPk)
    {
        $sql = "SELECT permission FROM cores_user_permission WHERE userFk=?
             UNION 
             SELECT permission FROM cores_group_permission gp
             INNER JOIN cores_group_user gu ON gp.groupFk=gu.groupFk AND gu.userFk=?";
        return $this->db->GetCol($sql, array($userPk, $userPk));
    }

    function updateUser($id, $data)
    {
        $account = arrData($data, 'account');
        $fullName = arrData($data, 'fullName');
        $depFk = arrData($data, 'depFk');
        $jobTitle = arrData($data, 'jobTitle');

        $groups = arrData($data, 'groups', array());
        $permissions = arrData($data, 'permissions', array());
        
        
    }

    function checkUniqueAccount($userPk, $account)
    {
        $inserted = $this->makeInstance()
                ->filterAccount($account)
                ->getEntity();

        if ($userPk && $inserted->pk == $userPk)
        {
            return true;
        }
        if (!$inserted->pk)
        {
            return true;
        }

        return false;
    }

}
