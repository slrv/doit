<?php


namespace Core\Exceptions;


use Throwable;

class ValidationException extends \Exception
{
    protected $errors;

    public function __construct( string $message, array $errors, $code = 0, Throwable $previous = null )
    {
        parent::__construct( $message, $code, $previous );
        $this->errors = $errors;
    }

    public function getErrors() {
        return $this->errors;
    }
}
