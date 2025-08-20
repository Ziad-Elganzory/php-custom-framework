<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Model;

// class User extends Model
// {

//     public function getUsers()
//     {
//         return $this->db->createQueryBuilder()
//         ->select('*')
//         ->from('users')
//         ->fetchAllAssociative();
//     }

//     public function getUserById(int $id)
//     {
//         return $this->db->createQueryBuilder()
//             ->select('*')
//             ->from('users')
//             ->where('id = :id')
//             ->setParameter('id', $id)
//             ->fetchAssociative();
//     }
    
//     public function create(string $user_name, string $first_name, string $last_name, string $email, string $password, string $date_of_birth, int $role)
//     {
//         $this->db->createQueryBuilder()
//             ->insert('users')
//             ->values([
//                 'user_name' => ':user_name',
//                 'first_name' => ':first_name',
//                 'last_name' => ':last_name',
//                 'email' => ':email',
//                 'password' => ':password',
//                 'date_of_birth' => ':date_of_birth',
//                 'role' => ':role',
//             ])
//             ->setParameters([
//                 'user_name' => $user_name,
//                 'first_name' => $first_name,
//                 'last_name' => $last_name,
//                 'email' => $email,
//                 'password' => $password,
//                 'date_of_birth' => $date_of_birth,
//                 'role' => $role,
//             ])
//             ->executeStatement();
//         return (int) $this->db->lastInsertId();
//     }

//     public function getUserRole(Role $role)
//     {
//         return $this->db->createQueryBuilder()
//             ->select('*')
//             ->from('users')
//             ->where('role = :role')
//             ->setParameter('role', $role->value)
//             ->fetchAllAssociative();
//     }
// }

class User extends Model
{
    protected $casts = [
        'role' => Role::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}