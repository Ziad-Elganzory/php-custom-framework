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
        $users = User::all()->toArray();

        return View::make('users/index',['users' => $users]);
    }

    #[Get('/users/{id}')]
    public function show(array $params)
    {
        $id = (int) $params['id'];
        $user = User::findOrFail($id);
        return View::make('users/show', ['user' => $user->toArray()]);
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
            (int) $_POST['role']
        ];

        $user = new User();

        $user->user_name = $user_name;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->password = $password;
        $user->date_of_birth = $date_of_birth;
        $user->role = $role;

        $user->save();

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

        header(header: "Location: /users/{$user->id}");
        exit;
    }
}