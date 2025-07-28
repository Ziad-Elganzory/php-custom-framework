<?php

declare(strict_types=1);

namespace App\Models;

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
    
    public function create(string $user_name, string $first_name, string $last_name, string $email, string $password, string $date_of_birth)
    {
        $stmt = $this->db->prepare('INSERT INTO users (user_name, first_name, last_name, email, password, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$user_name, $first_name, $last_name, $email, $password, $date_of_birth]);
        return (int) $this->db->lastInsertId();
    }
}