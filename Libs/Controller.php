<?php

namespace Libs;

abstract class Controller
{

    /** @var MvcContext */
    protected $context;

    /** @var \Slim\Http\Request */
    protected $req;

    /** @var \Slim\Http\Response */
    protected $res;

    /** @var \Libs\Session */
    protected $session;

    /** @var View */
    protected $view;
    protected $viewData = array();

    function __construct(MvcContext $context)
    {
        $this->context = $context;
        $this->req = $context->app->slim->request;
        $this->res = $context->app->slim->response;
        $this->session = new \Slim\Helper\Set($_SESSION);
        $this->session = new Session();

        $this->init();
    }

    /** run after __construct, tobe overrided */
    protected function init()
    {
        
    }

    protected function escape($str)
    {
        $str = stripslashes($str);
        $arr_search = array('&', '<', '>', '"', "'");
        $arr_replace = array('&amp;', '&lt;', '&gt;', '&#34;', '&#39;');
        $str = str_replace($arr_search, $arr_replace, $str);

        return $str;
    }

    protected function setView(View $view)
    {
        $this->view = $view;
        return $this;
    }

    protected function setViewData($arr)
    {
        $this->view->setData($arr);
        return $this;
    }

    protected function render($template)
    {
        $this->res->setBody($this->view->render($template));
    }

    protected function getCookie($name)
    {
        return call_user_func(array($this->context->app->slim, 'getCookie'), $name);
    }

    protected function setCookie($name, $value)
    {
        return call_user_func(array($this->context->app->slim, 'setCookie'), $name, $value);
    }

}
