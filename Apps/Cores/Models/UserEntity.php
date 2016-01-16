<?php

namespace Apps\Cores\Models;

use Libs\SQL\Entity;

class UserEntity extends Entity
{

    public $pk;
    public $fullName;
    public $jobTitle;
    public $depFk;
    public $account;
    public $pass;
    public $email;
    public $phone;
    public $stt;
    public $isAdmin;
    public $groups;
    public $permission;

    function __construct($rawData = null)
    {
        parent::__construct($rawData);
        $this->stt = $this->stt ? true : false;
    }

}
