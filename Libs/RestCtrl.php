<?php

namespace Libs;

class RestCtrl extends Controller
{

    protected function init()
    {
        parent::init();
        $this->resp->headers->set('Content-type', 'application/json');
        //allow request from every where
        $this->resp->headers->set('access-control-allow-origin', '*');
    }

    protected function restInput($key = null, $default = null)
    {
        $json = json_decode($this->req->getBody(), true);
        return $key === null ? $json : arrData($json, $key, $default);
    }

}
