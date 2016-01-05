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

}
