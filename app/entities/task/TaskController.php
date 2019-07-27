<?php

namespace Entities\Task;

use Core\Auth;
use Core\Exceptions\DeniedException;
use Core\Exceptions\MethodNotAllowedException;
use Core\Exceptions\NotAuthorizedException;
use Core\Exceptions\NotFoundException;
use Core\Exceptions\ValidationException;
use Core\Request;
use Core\Validation;
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
     * @throws ValidationException
     */
    public function post() {
        $this->methodIsAllowed( 'POST' );

        $body = Request::getBody();
        $this->createValidator( $body );

        return TaskRepository::createUserTask( $this->user_id, $body );
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     * @throws DeniedException
     */
    public function done() {
        $this->methodIsAllowed( 'PUT' );

        $task = TaskRepository::getOneTask( $this->entity_id );
        if ( !$task ) throw new NotFoundException( 'Task not found' );
        if ( $task->getIdUser() !== $this->user_id ) throw new DeniedException( 'Action denied for this user' );
        if ( $task->getDone() || $task->getDueDate() > new \DateTime() ) return 'Task not active';

        return TaskRepository::setDone( $this->entity_id );
    }

    /**
     * @throws DeniedException
     * @throws MethodNotAllowedException
     */
    public function delete() {
        $this->methodIsAllowed( 'DELETE' );

        $rows = TaskRepository::deleteTask( $this->entity_id, $this->user_id );
        if ( !$rows ) throw new DeniedException( 'No task found or no permission to delete it' );

        return "$rows task(s) was deleted";
    }

    /**
     * @param $body
     * @throws ValidationException
     * @throws \Exception
     */
    private function createValidator( $body ) {
        $validation_errors = [];
        if ( $error = Validation::minMax( 'title', $body, 6, 256 ) ) {
            $validation_errors[ 'title' ] = $error;
        }

        if ( $error = Validation::enum( 'priority', $body, [ 1, 2, 3 ] ) ) {
            $validation_errors[ 'priority' ] = $error;
        }

        if ( $error = Validation::dateBetween( 'due_date', $body ) ) {
            $validation_errors[ 'due_date' ] = $error;
        }

        if ( $validation_errors ) throw new ValidationException( $validation_errors );
    }
}
