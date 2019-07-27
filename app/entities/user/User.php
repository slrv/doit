<?php


namespace Entities\User;


class User
{
    private $userData;

    function __construct( array $data )
    {
        $this->userData = $data;
    }

    public function getId() {
        return $this->userData[ 'id' ] ?
            (int)$this->userData[ 'id' ] : null;
    }

    public function getEmail() {
        return $this->userData[ 'email' ] ?? null;
    }

    public function getPassword() {
        return $this->userData[ 'password' ] ?? null;
    }

    public function getToken() {
        return $this->userData[ 'token' ] ?? null;
    }

    public function getCreatedAt() {
        return $this->userData[ 'created' ] ?
            new \DateTime( $this->userData[ 'created' ] ) : null;
    }
}
