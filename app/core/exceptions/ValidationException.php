<?php


namespace Core\Exceptions;


use Throwable;

class ValidationException extends \Exception
{
    protected $errors;

    function __construct( array $errors, $code = 0, Throwable $previous = null )
    {
        parent::__construct( 'Validation error', $code, $previous );
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}
