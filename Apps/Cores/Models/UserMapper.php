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

}
