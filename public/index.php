<?php
declare(strict_types=1);

use App\App;
use App\Config;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use App\Exceptions\RouteNotFoundException;
use App\Router;
use App\View;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEWS_PATH', __DIR__ . '/../views');

$router = new Router();

$router
    ->get('/',[HomeController::class, 'index']);

(new App(
    $router,
    [
        'uri'=>$_SERVER['REQUEST_URI'],
        'method'=>$_SERVER['REQUEST_METHOD']
    ],
    new Config($_ENV)
))->run();