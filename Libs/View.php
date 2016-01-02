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

}
