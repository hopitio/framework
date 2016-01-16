<?php

namespace Config\Permissions;

class Cores extends \Libs\Permission
{

    static function getName()
    {
        return 'Hệ thống';
    }

    const MANAGE_USERS = 'Quản trị tài khoản, phòng ban, nhóm';
    const DYNAMIC_LIST = 'Quản trị danh mục động';

}
