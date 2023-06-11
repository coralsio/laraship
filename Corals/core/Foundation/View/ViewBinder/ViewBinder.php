<?php

namespace Corals\Foundation\View\ViewBinder;

interface ViewBinder
{
    /**
     * Bind the JavaScript variables to the view.
     *
     * @param string $js
     */
    public function bind($js);
}