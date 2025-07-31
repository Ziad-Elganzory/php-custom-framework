<?php

declare(strict_types=1);

namespace App\Attributes;

use App\Enums\HttpMethod;
use App\Interfaces\RouteInterface;
use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::IS_REPEATABLE)]
class Route implements RouteInterface
{
    public function __construct(public string $routePath, public HttpMethod $method = HttpMethod::GET)
    {}
}