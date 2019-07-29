<?php

namespace Core;

use Core\Exceptions\ActionNotAllowed;
use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotFoundException;
use Entities\AbstractController;

class Router
{
    const allowed_methods = [ "GET", "POST", "PUT", "DELETE" ];

    const entityMap = [
        'auth'  => 'User\AuthController',
        'task'  => 'Task\TaskController',
    ];

    private static $entity;
    private static $entity_id;
    private static $action;
    private static $httpMethod;

    private static $class;
    private static $method;

    /**
     * @return mixed
     * @throws ActionNotAllowed
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public static function dispatch() {
        $uri = trim( Request::getUri(), '/' );
        $requested_route = explode( '/', $uri );

        self::$entity = $requested_route[ 0 ];
        self::$entity_id = $requested_route[ 1 ] ?? null;
        self::$action = $requested_route[ 2 ] ?? null;

        self::getFunction();
        return self::execute();
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    private static function getFunction() {
        self::$httpMethod = Request::getMethod();
        if ( !in_array( self::$httpMethod, Router::allowed_methods ) ) throw new MethodNotAllowedException();

        if ( !isset( self::entityMap[ self::$entity ] ) ) throw new NotFoundException( 'Resource not found' );
        if ( self::$entity == 'auth' ) self::$action = self::$entity_id;
        self::$class = '\Entities\\'.self::entityMap[ self::$entity ];
        self::$method = self::$action ?? strtolower( self::$httpMethod );
    }

    /**
     * @throws ActionNotAllowed
     */
    private static function execute() {
        /** @var AbstractController $controller */
        $controller = new self::$class( self::$httpMethod, self::$entity_id );
        if ( !method_exists( $controller, self::$method ) ) throw new ActionNotAllowed();
        $func = self::$method;

        return $controller->$func();
    }
}
