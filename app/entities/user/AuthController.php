<?php


namespace Entities\User;


use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\ValidationException;
use Core\Request;
use Core\Validation;
use Entities\AbstractController;

class AuthController extends AbstractController
{
    /**
     * @throws MethodNotAllowedException
     */
    public function signIn() {
        $this->methodIsAllowed( 'POST' );


    }

    /**
     * @throws MethodNotAllowedException
     * @throws ValidationException
     */
    public function signUp() {
        $this->methodIsAllowed( 'POST' );

        $body = Request::getBody();
        $this->proceedInputValidation( $body );
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

        if ( $error = Validation::minMax( 'password', $body ) ) {
            $validation_errors[ 'email' ] = $error;
        }

        if ( $validation_errors ) {
            throw new ValidationException( 'Validation error', $validation_errors );
        }
    }
}
