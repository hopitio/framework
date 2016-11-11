<?php

namespace App\View\Layout;

class NoLayout extends Layout {

    protected function renderLayout($content) {
        echo $content;
    }

    public function themeUrl() {
        return null;
    }

}
