<?php
declare(strict_types=1);

use App\App;
use App\Config;
use App\Container;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Router;
use Dotenv\Dotenv;
// phpinfo();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEWS_PATH', __DIR__ . '/../views');


$container = new Container();
$router = new Router($container);

$router
    ->get('/',[HomeController::class, 'index'])
    ->get('/users',[UserController::class, 'index'])
    ->get('/users/{id}',[UserController::class, 'show'])
    ->get('/users/create',[UserController::class, 'create'])
    ->post('/users/store',[UserController::class, 'store']);

(new App(
    $router,
    [
        'uri'=>$_SERVER['REQUEST_URI'],
        'method'=>$_SERVER['REQUEST_METHOD']
    ],
    new Config($_ENV)
))->run();