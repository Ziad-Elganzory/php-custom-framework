<?php
declare(strict_types=1);

use App\App;
use App\Services\EmailService;
use Illuminate\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$container = new Container();

(new App($container))->boot();

$container->get(EmailService::class)->sendQueuedEmails();