<?php 

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

/**
 * @mixin PDO
 */
class DB
{
    private PDO $pdo;
    public function __construct(array $config)
    {
        $defaultOptions = [
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ];
        // Extract the actual driver name (e.g., 'mysql' from 'pdo_mysql')
        $driver = str_replace('pdo_', '', $config['driver']);
        
        try{
            $this->pdo = new PDO(
                $driver .':host='.$config['host'].';dbname='.$config['database'],
                $config['user'],
                $config['pass'],
                $config['options'] ?? $defaultOptions
            );
        } catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }

    public function __call(string $name, array $args)
    {
        return call_user_func_array([$this->pdo, $name], $args);
    }
}