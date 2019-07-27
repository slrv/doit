<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

spl_autoload_register( function ( $class ) {

    $levels = explode( '\\', $class );
    $class_name = array_pop( $levels ) . '.php';
    $namespace = $levels ? '/' . implode( '/', $levels ) : '';

    $file = realpath( __DIR__ . '/../app/' ) . strtolower( $namespace ) . '/' . $class_name;

    if ( file_exists( $file ) ) require $file;
});

App::run();
