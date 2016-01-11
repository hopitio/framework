<?php

namespace Libs;

abstract class Layout extends View
{

    protected $js = array();
    protected $css = array();

    function render($template, $data = null)
    {
        $content = parent::getOutput($template);
        $this->renderLayout($content);
    }

    abstract protected function renderLayout($content);

    abstract function themeUrl();

    function addJs($arr)
    {
        if (!is_array($arr))
        {
            $arr = array($arr);
        }
        $this->js = array_merge($this->js, $arr);
        return $this;
    }

    function addCss($arr)
    {
        if (!is_array($arr))
        {
            $arr = array($arr);
        }
        $this->css = array_merge($this->css, $arr);
        return $this;
    }

}
