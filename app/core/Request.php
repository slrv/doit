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
        return $_SERVER[ 'REQUEST_METHOD' ];
    }

    public static function getBody() {
        $body_str = file_get_contents('php://input');
        $body_arr = json_decode( $body_str, true );
        return (
            json_last_error() === JSON_ERROR_NONE &&
            is_array( $body_arr )
        ) ? $body_arr : [];
    }

    public static function getFields() {
        return array_merge( $_GET, $_POST );
    }
}
