<?php
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: Content-type, Authorization, http_authorization");

    exit(0);
}

spl_autoload_register( function ( $class ) {

    $levels = explode( '\\', $class );
    $class_name = array_pop( $levels ) . '.php';
    $namespace = $levels ? '/' . implode( '/', $levels ) : '';

    $file = realpath( __DIR__ . '/../app/' ) . strtolower( $namespace ) . '/' . $class_name;

    if ( file_exists( $file ) ) require $file;
});

App::run();
