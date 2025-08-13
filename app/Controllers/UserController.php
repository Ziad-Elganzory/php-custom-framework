<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Models\User;
use App\View;
use Symfony\Component\Mime\Address;

class UserController
{
    #[Get('/users')]
    public function index(): View
    {
        $users = (new User())->getUsers();

        return View::make('users/index',['users' => $users]);
    }

    #[Get('/users/{id}')]
    public function show(array $params)
    {
        $id = (int) $params['id'];
        $user = (new User())->getUserById($id);
        return View::make('users/show',['user' => $user]);
    }

    #[Get('/users/create')]
    public function create()
    {
        return View::make('users/create');
    }

    #[Post(routePath: '/users/store')]
    public function store()
    {
        [
            $user_name,
            $first_name,
            $last_name,
            $email,
            $password,
            $date_of_birth,
            $role
        ] = [
            $_POST['user_name'],
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['password'],
            $_POST['date_of_birth'],
            (int) $_POST['role'] // Convert string to enum
        ];
        
        $user = (new User())->create(
            $user_name,
            $first_name, 
            $last_name, 
            $email, 
            $password, 
            $date_of_birth,
            $role
        );

        $text = <<<Body
            Hello {$first_name} {$last_name},<br>
            
            Thank you for signing up!
        Body;

        $html = <<<HTML
            <h1 style="text-align: center; color: blue;">Welcome</h1>
            Hello $first_name,
            <br/>
            Thank you for signing up!
        HTML;

        (new \App\Models\Email())->queue(
            new Address($email),
            new Address('support@example.com'),
            'Welcome to Our Service',
            $html,
            $text,
        );

        header(header: "Location: /users/{$user}");
        exit;
    }
}