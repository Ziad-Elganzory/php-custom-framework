<?php

declare(strict_types=1);

namespace App\Enums;

enum Color : string
{
    case RED = 'red';
    case BLUE = 'blue';

    public function getColor()
    {
        return "color-$this->value";
    }
}