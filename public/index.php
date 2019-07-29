<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code( 204 );
    exit(0);
}

header("Content-Type: application/json; charset=UTF-8;");

spl_autoload_register( function ( $class ) {

    $levels = explode( '\\', $class );
    $class_name = array_pop( $levels ) . '.php';
    $namespace = $levels ? '/' . implode( '/', $levels ) : '';

    $file = realpath( __DIR__ . '/../app/' ) . strtolower( $namespace ) . '/' . $class_name;

    if ( file_exists( $file ) ) require $file;
});

App::run();
