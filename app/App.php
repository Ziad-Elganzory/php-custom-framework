<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use App\Exceptions\RouteNotFoundException;
use App\Interfaces\EmailValidationInterface;
use App\Services\MailerService;
use Dotenv\Dotenv;
use Symfony\Component\Mailer\MailerInterface;
use App\Services\Emailable\EmailValidationService as  EmailableEmailValidationService;
use App\Services\AbstractApi\EmailValidationService as AbstractApiEmailValidationService;


class App
{
    private Config $config;
    public function __construct(
        protected Container $container, 
        protected ?Router $router = null, 
        protected array $request = [], 
    ){}

    public function initDB(array $config)
    {

        $capsule = new Capsule;

        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher($this->container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    public function boot()
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__));
        $dotenv->load();
        $this->config = new Config($_ENV);

        $this->initDB($this->config->db);
        $this->container->bind(MailerInterface::class,fn()=> new MailerService($this->config->mailer['dsn']));
        //Swap Between Email Validation Services (Emailable , Abstract API)
        // Emailable Service
        // $this->container->bind(EmailValidationInterface::class, fn()=> new EmailableEmailValidationService($this->config->apiKeys['emailable']));
        //Abstract Api Service Provider
        $this->container->bind(EmailValidationInterface::class, fn()=> new AbstractApiEmailValidationService($this->config->apiKeys['abstract_api_email_validation']));
        return $this;
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