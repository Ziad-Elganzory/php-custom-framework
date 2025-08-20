<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Role;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $casts = [
        'role' => Role::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}