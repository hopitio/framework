<?php

namespace App\Controller\Rest;

use App\Model\UserEntity;
use App\Model\UserMapper;

class RestCtrl extends \Lib\RestCtrl {

    /**
     * Không dùng trực tiếp biến này, phải dùng ->user()
     * @var \App\Model\UserEntity 
     */
    private $user;
    private $userSeed = array();

    protected function init() {
        parent::init();
        $this->userSeed = $this->session->get('user');
    }

    protected function requireLogin() {
        if (!$this->user() || !$this->user()->pk) {
            $this->resp->setStatus(401);
            $this->resp->setBody('requireLogin');
            return false;
        }

        return true;
    }

    /** @var \App\Model\UserEntity */
    protected function user() {
        if (!$this->userSeed) {
            return new UserEntity;
        }

        if (!$this->user) {
            $user = UserMapper::makeInstance()
                    ->filterPk($this->userSeed['pk'])
                    ->getEntity();
            if ($user->pass != $this->userSeed['pass']) {
                return new UserEntity;
            }

            $this->user = $user;
        }

        return $this->user;
    }

    protected function requireAdmin() {
        if (!$this->requireLogin()) {
            return false;
        }

        if (!$this->user()->isAdmin) {
            $this->resp->setStatus(403);
            $this->resp->setBody('Ban khong phai admin');
            return false;
        }

        return true;
    }

}
