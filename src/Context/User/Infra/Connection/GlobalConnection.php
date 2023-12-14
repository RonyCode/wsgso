<?php

namespace Gso\Ws\Context\User\Infra\Connection;

use Gso\Ws\Context\User\Infra\Connection\Interfaces\GlobalConnectionInterface;
use PDO;

class GlobalConnection implements GlobalConnectionInterface
{
    private static $instance = null;

    public static function conn(): \PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO(
                getenv('DBDRIVE') . ':host=' . getenv('DBHOST') . ';dbname=' . getenv('DBNAME'),
                getenv('DBUSER'),
                getenv('DBPASS'),
                [
                    \PDO::ATTR_PERSISTENT         => true,
                    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_EMULATE_PREPARES   => true,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
                ]
            );
        }

        return self::$instance;
    }

    protected function __construct()
    {
    }
}
