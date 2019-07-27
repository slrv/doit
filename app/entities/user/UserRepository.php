<?php

namespace Entities\User;

use Core\DbConnection;

class UserRepository
{
    public static function registerUser( string $email, string $password ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare( 'insert into user ( email, password, token, created ) values ( ?, ?, ?, now() )' );
        $pass = password_hash( $password, PASSWORD_BCRYPT );
        $token = md5( $email.time() );
        $stmt->bind_param( 'sss', $email, $pass, $token );
        $stmt->execute();
    }

    public static function getUserByToken( string $token ) {
        return self::getUser( 'token', $token );
    }

    public static function getUserByEmail( string $email ) {
        return self::getUser( 'email', $email );
    }

    public static function getUserById( int $id ) {
        return self::getUser( 'id', $id );
    }

    private static function getUser( string $field, string $value ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->query( "select * from user where $field = $value limit 1" );
        return $stmt ? $stmt->fetch_assoc() : null;
    }
}
