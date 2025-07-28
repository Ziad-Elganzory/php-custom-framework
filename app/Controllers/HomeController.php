<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\View;

class HomeController
{
    #[Get('/')]
    public function index(): View
    {
        return View::make('index');
    }
}