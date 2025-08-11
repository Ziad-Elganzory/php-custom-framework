<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\RouteNotFoundException;
use App\Services\MailerService;
use Dotenv\Dotenv;
use PDO;
use PDOException;
use Symfony\Component\Mailer\MailerInterface;

class App
{
    private static DB $db;
    private Config $config;
    public function __construct(
        protected Container $container, 
        protected ?Router $router = null, 
        protected array $request = [], 
    ){}

    public function boot()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $this->config = new Config($_ENV);
        static::$db = new DB($this->config->db ?? []);

        $this->container->set(MailerInterface::class,fn()=> new MailerService($this->config->mailer['dsn']));
        return $this;
    }

    public static function db(): DB
    {
        return static::$db;
    }

    public function run()
    {
        try{
            echo $this->router->resolve($this->request['uri'],strtolower($this->request['method']));
        } catch (RouteNotFoundException){
            http_response_code(404);
            echo View::make('errors/404');
        }

    }
}