<?php

namespace Entities\Task;

use Core\Auth;
use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotAuthorizedException;
use Entities\AbstractController;

class TaskController extends AbstractController
{
    private $user_id;

    public function __construct( $method, $entity_id = null )
    {
        parent::__construct( $method, $entity_id );
        if ( !Auth::loggedIn() ) throw new NotAuthorizedException( Auth::getError() );
        $this->user_id = Auth::getUser()->getId();
    }

    public function get() {
        return ( $this->entity_id ) ?
            $this->getOne() : $this->getList();
    }

    /**
     * @throws MethodNotAllowedException
     */
    private function getOne() {
        throw new MethodNotAllowedException();
    }

    /**
     * @return array
     */
    private function getList() {
        return TaskRepository::getUserTasks( $this->user_id );
    }

    /**
     * @throws MethodNotAllowedException
     */
    private function post() {
        $this->methodIsAllowed( 'POST' );
    }
}
