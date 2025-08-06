<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Route;
use App\Enums\Role;
use App\Enums\Roles;
use App\Models\User;
use App\Services\MailerService;
use App\View;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class UserController
{

    public function __construct(protected MailerInterface $mailer)
    {

    }
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
        
        // $user = (new User())->create(
        //     $user_name,
        //     $first_name, 
        //     $last_name, 
        //     $email, 
        //     $password, 
        //     $date_of_birth,
        //     $role
        // );

        // header(header: "Location: /users/{$user}");
        // exit;

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

        $email = (new Email())
                    ->from('support@example.com')
                    ->to($email)
                    ->subject('Welcome')
                    ->text($text)
                    ->html($html);

        $this->mailer->send($email);
    }
}