<?php

namespace Libs;

class View extends \Slim\View
{

    /** @var MvcContext */
    protected $context;

    /**
     * 
     * @param \Libs\MvcContext $context
     */
    function __construct(MvcContext $context)
    {
        parent::__construct();
        $this->context = $context;
        $this->init();
    }

    protected function init()
    {
        
    }

    function render($template, $data = null)
    {
        $this->context->app->slim->response->setBody(parent::render($template));
    }

    function getOutput($template)
    {
        return parent::render($template);
    }

}
