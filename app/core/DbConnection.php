<?php

namespace Core;

use Exception;

class DbConnection
{
    private static $connection;

    /**
     * @throws Exception
     */
    public static function createConnection() {
        $settings_file = realpath( __DIR__ . '/../../settings.php' );
        if ( !is_file( $settings_file ) ) throw new Exception( 'Settings file not found' );

        $settings = require $settings_file;
        if ( empty( $settings[ 'db' ] ) ) throw new Exception( 'Connection to database not configured' );

        $dbSettings = $settings[ 'db' ];
        foreach ( [ 'host', 'username', 'password', 'database' ] as $config ) {
            if ( !isset( $dbSettings[ $config ] ) ) throw new Exception( 'Database configuration looks wrong' );
        }

        mysqli_report( MYSQLI_REPORT_STRICT );
        self::$connection = new \mysqli( $dbSettings[ 'host' ], $dbSettings[ 'username' ], $dbSettings[ 'password' ], $dbSettings[ 'database' ] );
    }

    public static function getConnection(): \mysqli {
        return self::$connection;
    }
}
