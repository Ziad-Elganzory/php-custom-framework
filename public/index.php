<?php
declare(strict_types=1);

use App\App;
use App\Controllers\CurlController;
// use App\Container;
use Illuminate\Container\Container;
use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Router;


require_once __DIR__ . '/../vendor/autoload.php';

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEWS_PATH', __DIR__ . '/../views');


$container = new Container();
$router = new Router($container);

$router->registerRouteFromControllerAttribute([
    HomeController::class,
    UserController::class,
    CurlController::class
]);

(new App(
    $container,
    $router,
    [
        'uri'=>$_SERVER['REQUEST_URI'],
        'method'=>$_SERVER['REQUEST_METHOD']
    ],
))->boot()->run();