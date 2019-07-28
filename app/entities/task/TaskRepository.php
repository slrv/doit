<?php

namespace Entities\Task;

use Core\DbConnection;
use Core\Exceptions\ServiceUnavailableException;
use Entities\Paginator;

class TaskRepository
{
    /**
     * @param int $id
     * @param array $filters
     * @return array
     * @throws \Exception
     */
    public static function getUserTasks( int $id, array $filters ) {
        $rules = "where id_user = $id";
        if ( isset( $filters[ 'onlyActive' ] ) ) {
            $rules .= " and due_date > now() and done_date is null";
        }
        $paginationResult = new Paginator( 'task', $rules );

        return $paginationResult
            ->setOrder( [ 'priority', 'due_date', 'created_at', 'done_date' ] )
            ->proceed()
            ->getData();
    }

    /**
     * @param int $id
     * @return Task|null
     */
    public static function getOneTask( int $id ): ?Task {
        $conn = DbConnection::getConnection();
        $stmt = $conn->query( "select * from task where id = $id limit 1" );

        $result = $stmt->fetch_assoc();
        return ( $result ) ? new Task( $result ) : null;
    }

    /**
     * @param int $user_id
     * @param array $data
     * @return array|null
     * @throws ServiceUnavailableException
     */
    public static function createUserTask( int $user_id, array $data ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare(
            "insert into task ( id_user, title, priority, due_date, created_at ) 
                    values ( $user_id, ?, ?, ?, now() )"
        );
        $stmt->bind_param( 'sis', $data[ 'title' ], $data[ 'priority' ], $data[ 'due_date' ] );

        if ( !$stmt->execute() ) throw new ServiceUnavailableException( 'Something going wrong. Try later' );

        $res_stmt = $conn->query( "select * from task where id_user = $user_id order by created_at desc limit 1" );
        return $res_stmt->fetch_assoc();
    }

    /**
     * @param int $id
     * @return array
     * @throws ServiceUnavailableException
     */
    public static function setDone( int $id ) {
        $conn = DbConnection::getConnection();
        if ( !$conn->query( "update task set done_date = now() where id = $id" ) ) {
            throw new ServiceUnavailableException( 'Something going wrong. Try later' );
        }

        return self::getOneTask( $id )->getInitData();
    }

    /**
     * @param int $id
     * @param int $user_id
     * @return int
     */
    public static function deleteTask( int $id, int $user_id  ) {
        $conn = DbConnection::getConnection();
        $stmt = $conn->prepare( "delete from task where id = ? and id_user = ?" );
        $stmt->bind_param( 'ii', $id, $user_id );
        $stmt->execute();

        return $stmt->affected_rows;
    }
}
