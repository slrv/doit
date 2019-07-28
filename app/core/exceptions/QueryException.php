<?php


namespace Core\Exceptions;


use Throwable;

class QueryException extends \Exception
{
    protected $query;
    protected $error;

    function __construct( $error, $query )
    {
        parent::__construct( 'MySQL error' );
        $this->error = $error;
        $this->query = $query;
    }

    public function getQuery() {
        return $this->query;
    }

    public function getError() {
        return $this->error;
    }
}
