<?php


namespace Entities\User;


use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotAuthorizedException;
use Core\Exceptions\ValidationException;
use Core\Request;
use Core\Validation;
use Entities\AbstractController;

class AuthController extends AbstractController
{
    /**
     * @throws MethodNotAllowedException
     * @throws NotAuthorizedException
     * @throws ValidationException
     */
    public function signIn() {
        $this->methodIsAllowed( 'POST' );

        $body = Request::getBody();
        $this->proceedInputValidation( $body );
        $user = UserRepository::getUserByEmail( $body[ 'email' ] );
        if ( !$user ) throw new NotAuthorizedException( 'User not registered' );
        if ( !password_verify( $body[ 'password' ], $user->getPassword() ) ) {
            throw new NotAuthorizedException( 'Wrong password' );
        }

        return $user->getToken();
    }

    /**
     * @throws MethodNotAllowedException
     * @throws ValidationException
     */
    public function signUp() {
        $this->methodIsAllowed( 'POST' );

        $body = Request::getBody();
        $this->proceedInputValidation( $body );
        return UserRepository::registerUser( $body );
    }

    /**
     * @param array $body
     * @throws ValidationException
     */
    private function proceedInputValidation( array $body ) {
        $validation_errors = [];
        if ( $error = Validation::email( 'email', $body ) ) {
            $validation_errors[ 'email' ] = $error;
        }

        if ( $error = Validation::minMax( 'password', $body, 6, 10 ) ) {
            $validation_errors[ 'password' ] = $error;
        }

        if ( $validation_errors ) {
            throw new ValidationException( $validation_errors );
        }
    }
}
