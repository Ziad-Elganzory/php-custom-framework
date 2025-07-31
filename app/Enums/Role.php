<?php

declare(strict_types=1);

namespace App\Enums;

enum Role : string
{
    case ADMIN = '0';
    case USER = '1';

    public function toString()
    {
        return match($this){
            self::ADMIN => 'admin',
            self::USER => 'user'
        };
    }

    public function color(): Color
    {
        return match($this) {
            self::ADMIN => Color::RED,
            self::USER => Color::BLUE
        };
    }
}