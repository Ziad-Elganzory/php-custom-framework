<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Models\User;
use App\View;

class UserController
{
    public function index(): View
    {
        $users = (new User())->getUsers();

        return View::make('users/index',['users' => $users]);
    }

    public function show(array $params)
    {
        $id = (int) $params['id'];
        $user = (new User())->getUserById($id);
        return View::make('users/show',['user' => $user]);
    }

    public function create()
    {
        return View::make('users/create');
    }

    public function store()
    {
        [
            $user_name,
            $first_name,
            $last_name,
            $email,
            $password,
            $date_of_birth
        ] = [
            $_POST['user_name'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['date_of_birth']
        ];

        $user = (new User())->create(
            $user_name,
            $first_name, 
            $last_name, 
            $email, 
            $password, 
            $date_of_birth
        );

        header(header: "Location: /users/{$user}");
        exit;
    }
}