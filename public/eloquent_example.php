<?php 

declare(strict_types=1);

use App\Enums\Role;
use App\Models\User;

require_once __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../eloquent.php';

$userID = 1;
// echo User::query()
//     ->where('id','=', $userID)
//     ->update([
//         'role' => Role::ADMIN
//     ]);
User::query()->where('role',Role::ADMIN)->get()->each(function(User $user){
    // echo $user->user_name . PHP_EOL;
    echo $user->first_name . ' ' . $user->last_name . ' - ' . $user->email . ' - ' . $user->role ->toString() . ' - ' . $user->created_at->format('m/d/Y').PHP_EOL;
});

$user = new User();
// $user->user_name = 'jane_doe';
// $user->first_name = 'Jane';
// $user->last_name = 'Doe';
// $user->email = 'jane.doe@example.com';
// $user->password = 'securepassword';
// $user->date_of_birth = '1990-01-01';
// $user->role = Role::ADMIN;

// $user->save();
