<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Views\Layouts\TwoColsLayout;

abstract class CoresCtrl extends \Libs\Controller
{

    /** @var TwoColsLayout */
    protected $twoColsLayout;

    protected function init()
    {
        $this->twoColsLayout = new TwoColsLayout($this->context);
        $this->twoColsLayout->setTemplatesDirectory(dirname(__DIR__) . '/Views');
        $this->twoColsLayout->setBrand('Test');
    }

}
