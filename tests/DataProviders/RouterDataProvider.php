<?php

namespace Tests\DataProviders;

use App\Enums\HttpMethod;

class RouterDataProvider
{
    public static function routeNotFoundCases(): array
    {
        return [
            ['/users',HttpMethod::PUT->value],
            ['/invoices',HttpMethod::POST->value],
            ['/users',HttpMethod::GET->value],
            ['/users',HttpMethod::POST->value]
        ];
    }
}