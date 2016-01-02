<?php

namespace Apps\Cores\Controllers;

use Apps\Cores\Views\Layouts\TwoColsLayout;

abstract class CoresCtrl extends \Libs\Controller
{

    protected function init()
    {
        $this->view = new TwoColsLayout($this->context);
        $this->view->setTemplatesDirectory(dirname(__DIR__) . '/Views');
    }

}
