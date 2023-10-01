<?php

namespace Gso\Ws\Context\User\Infra\Connection;

use Gso\Ws\Context\User\Infra\Interfaces\GlobalConnectionInterface;

class GlobalConnection implements GlobalConnectionInterface
{
    public function __construct()
    {
        $this->conn();
//        $this->manualConnection('mysql', 'localhost', 'gsoBackHomologacao', 'root', '170286P@ra');
    }

    public function conn(): \PDO
    {
        return new \PDO(
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

    public function manualConnection($drive, $host, $dbName, $user, $pass): \PDO
    {
        return new \PDO(
            $drive . ':host=' . $host . ';dbname=' . $dbName,
            $user,
            $pass,
            [
                \PDO::ATTR_PERSISTENT         => true,
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_EMULATE_PREPARES   => true,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ]
        );
    }
}
