<?php

namespace Entities\User;

use Core\DbConnection;
use Core\Exceptions\ValidationException;

class UserRepository
{
    /**
     * @param array $data
     * @return string
     * @throws ValidationException
     */
    public static function registerUser( array $data )
    {
        $email = $data[ 'email' ];
        $password = $data[ 'password' ];
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare( 'insert into user ( email, password, token, created ) values ( ?, ?, ?, now() )' );
        $pass = password_hash( $password, PASSWORD_BCRYPT );
        $token = md5( $email . time() );
        $stmt->bind_param( 'sss', $email, $pass, $token );
        if ( !$stmt->execute() && $stmt->errno == 1062 ) {
            throw new ValidationException( [ 'email' => 'User exists' ] );
        }

        return $token;
    }

    public static function getUserByToken( string $token ): ?User {
        return self::getUser( 'token', $token );
    }

    public static function getUserByEmail( string $email ): ?User {
        return self::getUser( 'email', $email );
    }

    public static function getUserById( int $id ): ?User {
        return self::getUser( 'id', $id );
    }

    private static function getUser( string $field, string $value ): ?User {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare( "select * from user where `$field` = ? limit 1" );
        $stmt->bind_param( 's', $value );
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result ? new User( $result ) : null;
    }
}
