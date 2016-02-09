<?php

namespace Apps\Cores\Controllers;

class LoginCtrl extends CoresCtrl
{
    protected $loginLayout;
    function init()
    {
        parent::init();
        $this->loginLayout = new LoginLayout;
    }
}
