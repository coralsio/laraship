<?php namespace Corals\Theme\Exceptions;

class themeNotFound extends \Exception
{

    public function __construct($themeName)
    {
        parent::__construct(trans('Theme::exception.theme.theme_not_found',['themeName' => $themeName]), 1);
    }

}