<?php

namespace Entities\Task;

use Core\DbConnection;

class TaskRepository
{
    public static function getUserTasks( int $id, array $filters ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->query( "select * from task where id_user = $id" );

        $result = [];
        while ( $row = $stmt->fetch_assoc() ) {
            array_push( $result, $row );
        }
        return $result;
    }

    public static function getOneTask( int $id ): ?Task {
        $conn = DbConnection::getConnection();
        $stmt = $conn->query( "select * from task where id = $id limit 1" );

        $result = $stmt->fetch_assoc();
        return ( $result ) ? new Task( $result ) : null;
    }

    public static function createUserTask( int $user_id, array $data ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare(
            "insert into task ( id_user, title, priority, due_date, created_at ) 
                    values ( $user_id, ?, ?, ?, now() )"
        );
        $stmt->bind_param( 'sis', $data[ 'title' ], $data[ 'priority' ], $data[ 'due_date' ] );
        return $stmt->execute();
    }

    public static function setDone( int $id ) {
        $conn = DbConnection::getConnection();
        return $stmt = $conn->query( "update task set done_date = now() where id = $id" );
    }

    public static function deleteTask( int $id, int $user_id  ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare( "delete from task where id = ? and id_user = ?" );
        $stmt->bind_param( 'ii', $id, $user_id );
        $stmt->execute();

        return $stmt->affected_rows;
    }
}
