<?php


namespace Entities\Task;


class Task
{
    private $taskData;

    function __construct( array $taskData )
    {
        $this->taskData = $taskData;
    }

    public function getId() {
        return $this->taskData[ 'id' ] ?
            (int)$this->taskData[ 'id' ] : null;
    }

    public function getIdUser() {
        return $this->taskData[ 'id_user' ] ?
            (int)$this->taskData[ 'id_user' ] : null;
    }

    public function getTitle() {
        return $this->taskData[ 'title' ] ?
            $this->taskData[ 'title' ] : null;
    }

    public function getPriority() {
        return $this->taskData[ 'priority' ] ?
            (int)$this->taskData[ 'priority' ] : null;
    }

    public function getDueDate() {
        return $this->taskData[ 'due_date' ] ?
            $this->taskData[ 'due_date' ] : null;
    }

    public function getDone() {
        return $this->taskData[ 'done_date' ] ?
            $this->taskData[ 'done_date' ] : null;
    }
}
