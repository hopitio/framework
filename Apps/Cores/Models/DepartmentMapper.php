<?php

namespace Apps\Cores\Models;

use Libs\SQL\Mapper;

class DepartmentMapper extends Mapper
{

    public function makeEntity($rawData)
    {
        return new DepartmentEntity($rawData);
    }

    public function tableAlias()
    {
        return 'dep';
    }

    public function tableName()
    {
        return 'cores_department';
    }

}
