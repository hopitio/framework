<?php

namespace Libs;

abstract class Layout extends View
{

    function render($template, $data = null)
    {
        
        $content = parent::render($template);
        $this->renderLayout($content);
    }
    
    abstract protected function renderLayout($content);
}
