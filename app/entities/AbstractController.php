<?php


namespace Entities;


use Core\Exceptions\MethodNotAllowedException;

abstract class AbstractController
{
    protected $method;
    protected $entity_id;

    function __construct( $method, $entity_id = null )
    {
        $this->method = $method;
        $this->entity_id = $entity_id;
    }

    /**
     * @param string $method
     * @throws MethodNotAllowedException
     */
    protected function methodIsAllowed( string $method ) {
        if ( $this->method != $method ) throw new MethodNotAllowedException();
    }
}
