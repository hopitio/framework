<?php

namespace Apps\Cores\Controllers\Rest;

use Libs\Controller;

class RestCtrl extends Controller
{

    protected function init()
    {
        parent::init();
        $this->res->headers->set('Content-type', 'application/json');
        //allow request from every where
        $this->res->headers->set('access-control-allow-origin', '*');
    }

}
