<?php

namespace Apps\Cores\Models;

use Libs\SQL\Mapper;

class GroupMapper extends Mapper
{

    public function makeEntity($rawData)
    {
        return new GroupEntity($rawData);
    }

    public function tableAlias()
    {
        return 'gp';
    }

    public function tableName()
    {
        return 'cores_group';
    }

    function filterPk($id)
    {
        $this->where('gp.pk=?', __FUNCTION__)->setParam($id, __FUNCTION__);
        return $this;
    }

    function filterStatus($bool)
    {
        if ($bool != -1)
        {
            $this->where('gp.stt=?', __FUNCTION__)->setParam($bool ? 1 : 0, __FUNCTION__);
        }
        return $this;
    }

    /** @return UserEntity */
    function loadUsers($groupPk)
    {
        return UserMapper::makeInstance()
                        ->innerJoin('cores_group_user gu ON u.pk=gu.userFk AND gu.groupFk=' . intval($groupPk))
                        ->getAll();
    }

    function updateGroup($pk, $data)
    {
        $update['groupCode'] = arrData($data, 'groupCode');
        $update['groupName'] = arrData($data, 'groupName');
        $update['stt'] = arrData($data, 'stt') ? 1 : 0;

        if (!$update['groupCode'] || !$update['groupName'])
        {
            return false;
        }

        $pk = $this->replace($pk, $update);

        if ($pk)
        {
            $this->db->delete('cores_group_user', 'groupFk=?', array($pk));
            foreach (arrData($data, 'users') as $user)
            {
                $this->db->insert('cores_group_user', array(
                    'userFk'  => $user,
                    'groupFk' => $pk
                ));
            }
        }

        return $pk;
    }

}
