<?php namespace Corals\Theme\Exceptions;

class themeAlreadyExists extends \Exception
{

    public function __construct($theme)
    {
        parent::__construct(trans('Theme::exception.theme.theme_exception', ['name' => $theme->name]), 1);
    }

}