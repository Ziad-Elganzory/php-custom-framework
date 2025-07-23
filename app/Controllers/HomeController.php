<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Models\Invoice;
use App\Models\SignUp;
use App\Models\User;
use App\View;
use Exception;
use PDO;
use PDOException;
use Throwable;

class HomeController
{
    public function index(): View
    {

        return View::make('index');
    }
}