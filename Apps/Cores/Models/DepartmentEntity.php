<?php

namespace Apps\Cores\Models;

use Libs\SQL\Entity;

class DepartmentEntity extends Entity
{

    public $pk;
    public $depCode;
    public $depName;
    public $depFk;
    public $sort;
    public $pathSort;
    public $path;
    public $stt;

    /** @var UserEntity */
    public $users = array();

    /** @var DepartmentEntity */
    public $deps = array();

    /** @var DepartmentEntity */
    public $ancestors = array();

}
