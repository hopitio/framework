<?php

namespace Apps\Cores\Controllers\Rest;

use Apps\Cores\Models\UserEntity;

class RestCtrl extends \Libs\RestCtrl
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
        if (!$this->user || !$this->user->pk)
        {
            $this->resp->setStatus(401);
            $this->resp->setBody('requireLogin');
            return false;
        }

        return true;
    }

    protected function requireAdmin()
    {
        if (!$this->requireLogin())
        {
            return false;
        }

        if (!$this->user->isAdmin)
        {
            $this->resp->setStatus(403);
            $this->resp->setBody('Ban khong phai admin');
            return false;
        }

        return true;
    }

}
