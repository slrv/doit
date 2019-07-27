<?php

use Core\Auth;
use Core\DbConnection;
use Core\Response;
use Core\Router;

class App
{
    public static function run() {
        try {
            DbConnection::createConnection();
            Auth::checkAuth();
            $result = Router::dispatch();
            Response::success( $result );
        } catch ( Exception $e ) {
            Response::error( $e );
        }
    }
}
