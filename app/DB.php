<?php 

declare(strict_types=1);

namespace App;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\ConnectionException;

;

/**
 * @mixin Connection
 */
class DB
{
    private Connection $connection;
    public function __construct(array $config)
    {
        try {
            $this->connection = DriverManager::getConnection($config);
        } catch (ConnectionException $e) {
            throw $e;
        }
    }

    public function __call(string $name, array $args)
    {
        return call_user_func_array([$this->connection, $name], $args);
    }
}