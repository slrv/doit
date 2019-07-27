<?php


namespace Core\Exceptions;


use Throwable;

class ActionNotAllowed extends \Exception
{
    function __construct( $message = "", $code = 0, Throwable $previous = null )
    {
        parent::__construct( 'Action not allowed', $code, $previous );
    }
}
