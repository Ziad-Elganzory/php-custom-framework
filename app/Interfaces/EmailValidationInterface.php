<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\EmailValidationResult;

interface EmailValidationInterface
{
    public function verify(string $email): EmailValidationResult;
}