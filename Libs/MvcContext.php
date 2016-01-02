<?php

namespace Libs;

class MvcContext
{

    /** @var Bootstrap */
    public $app;
    public $method;
    public $controller;
    public $action;
    public $rewriteBase;

    /**
     * 
     * @param type $method
     * @param type $controller
     * @param type $action
     */
    function __construct($method, $controller, $action)
    {
        $this->method = $method;
        $this->controller = $controller;
        $this->action = $action;
    }

}
