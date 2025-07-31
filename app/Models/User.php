<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;

class User extends Model
{

    public function getUsers()
    {
        $stmt = $this->db->prepare('SELECT * FROM users');
        $stmt->execute();
        // return $stmt->fetchAll();
        return $this->fetchLazy($stmt);
    }

    public function getUserById(int $id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create(string $user_name, string $first_name, string $last_name, string $email, string $password, string $date_of_birth, int $role)
    {
        $stmt = $this->db->prepare('INSERT INTO users (user_name, first_name, last_name, email, password, date_of_birth, role) VALUES (?, ?, ?, ?, ?, ?,?)');
        $stmt->execute([$user_name, $first_name, $last_name, $email, $password, $date_of_birth, $role]);
        return (int) $this->db->lastInsertId();
    }

    public function getUserRole(Role $role)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE role = ?');
        $stmt->execute([$role->value]);
        return $stmt->fetchAll();
    }
}