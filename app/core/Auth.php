<?php


namespace Core;


use Entities\User\User;
use Entities\User\UserRepository;

class Auth
{
    const AUTH_HEADER = 'Authorization';

    private static $user;
    private static $isAuthed = false;
    private static $error;

    public static function checkAuth() {
        if( Request::hasHeader( self::AUTH_HEADER ) ) {
            $token = Request::getHeaderValue( self::AUTH_HEADER );
            $user = UserRepository::getUserByToken( $token );
            if ( $user ) {
                self::$user = new User( $user );
                self::$isAuthed = true;
            } else {
                self::$error = 'Invalid token';
            }
        } else {
            self::$error = 'Token not provided';
        }
    }

    public static function loggedIn(): bool {
        return self::$isAuthed;
    }

    public static function getUser(): ?User {
        return self::$user;
    }

    public static function getError(): ?string {
        return self::$error;
    }
}
