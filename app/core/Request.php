<?php


namespace Core;


class Request
{
    public static function getUri() {
        $path = str_replace( '?'.$_SERVER[ 'QUERY_STRING'], '', $_SERVER[ 'REQUEST_URI' ] );
        return $path;
    }

    public static function hasHeader( string $name ): bool {
        return !empty( $_SERVER[ $name ] );
    }

    public static function getHeaderValue( string $name ): ?string {
        return $_SERVER[ $name ] ?? null;
    }

    public static function getMethod() {
        $realMethod = $_SERVER[ 'REQUEST_METHOD' ];
        return (
            $realMethod == 'OPTIONS' &&
            self::hasHeader( 'Access-Control-Request-Method' )
        ) ? self::getHeaderValue( 'Access-Control-Request-Method' ) : $realMethod;
    }

    public static function getBody() {
        $body_str = file_get_contents('php://input');
        return $body_str ? json_decode( $body_str, true ): [];
    }

    public static function getFields() {
        return array_merge( $_GET, $_POST );
    }
}
