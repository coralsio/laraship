<?php namespace Corals\Theme\Exceptions;
/**
 * Define a custom exception class
 */
class themeException extends \Exception
{

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null)
    {

        $message .= trans('Theme::exception.theme.theme_exception_extend', ['theme' => \Theme::get()]);

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }


}