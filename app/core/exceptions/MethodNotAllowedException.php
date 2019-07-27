<?php

namespace Core\Exceptions;

use Exception;
use Throwable;

class MethodNotAllowedException extends Exception
{
    function __construct( $message = "", $code = 0, Throwable $previous = null )
    {
        parent::__construct( "Method not allowed", $code, $previous );
    }
}
