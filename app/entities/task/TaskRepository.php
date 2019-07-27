<?php

namespace Entities\Task;

use Core\DbConnection;

class TaskRepository
{
    public static function getUserTasks( int $id ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->query( "select * from task where id_user = $id" );

        $result = [];
        while ( $row = $stmt->fetch_assoc() ) {
            array_push( $result, $row );
        }
        return $result;
    }
}
