<?php

namespace Core;

use Core\Exceptions\ActionNotAllowed;
use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotAuthorizedException;
use Core\Exceptions\NotFoundException;
use Core\Exceptions\ValidationException;
use Exception;

class Response
{
    public static function success( $data = null ) {
        echo json_encode( [
            'success'   => true,
            'data'      => $data
        ] );
    }

    public static function error( Exception $e ) {
        switch ( true ) {
            case ( $e instanceof NotFoundException ):
                http_response_code( 404 );
                $message = $e->getMessage();
                break;

            case ( $e instanceof MethodNotAllowedException ):
                http_response_code( 405 );
                $message = $e->getMessage();
                break;

            case ( $e instanceof ActionNotAllowed ):
                http_response_code( 400 );
                $message = $e->getMessage();
                break;

            case ( $e instanceof ValidationException ):
                http_response_code( 400 );
                $message = $e->getMessage();
                $errors = $e->getErrors();
                break;

            case ( $e instanceof NotAuthorizedException ):
                http_response_code( 401 );
                $message = $e->getMessage();
                break;

            default:
                http_response_code( 500 );
                $message = 'Server error';
        }

        echo json_encode( [
            'success'   => false,
            'message'   => $message,
            'error'     => $errors ?? null
        ] );
    }
}
