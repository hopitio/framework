<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\RestCtrl;

class RestCtrl extends RestCtrl
{

    /** @var Apps\Cores\Models\UserEntity */
    protected $user;

    protected function init()
    {
        parent::init();
        $this->user = new UserEntity($this->session->get('user'));
    }

    protected function requireLogin()
    {
        
    }

}
