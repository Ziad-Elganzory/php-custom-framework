<?php

namespace Tests\Unit;

use App\Container;
use App\Enums\HttpMethod;
use App\Exceptions\RouteNotFoundException;
use App\Router;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;
use Tests\DataProviders\RouterDataProvider;

class RouterTest extends TestCase
{
    protected Router $router;
    protected Container $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = new Container();
        $this->router = new Router($this->container);
    }
    public function test_that_it_can_register_a_route()
    {
        $this->router->register(HttpMethod::GET, '/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    public function test_that_it_registers_a_get_route()
    {
        $this->router->get('/users', ['Users', 'index']);

        $expected = [
            'get' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    public function test_that_it_registers_a_post_route()
    {
        $this->router->post('/users', ['Users', 'index']);

        $expected = [
            'post' => [
                '/users' => ['Users', 'index']
            ]
        ];

        $this->assertSame($expected, $this->router->routes());
    }

    public function test_that_there_are_no_routes_when_router_is_created()
    {
        $this->assertEmpty((new Router($this->container))->routes());
    }

    #[DataProviderExternal(RouterDataProvider::class,'routeNotFoundCases')]
    public function test_that_it_throws_not_found_exception(string $requestUri, string $requestMethod)
    {
        $users = new class() {
            public function delete(): bool
            {
                return true;
            }
        };
        $this->router->post('/users', [$users::class, 'store']);
        $this->router->get('/users', ['Users', 'index']);
        $this->expectException(RouteNotFoundException::class);
        $this->router->resolve($requestUri, $requestMethod);
    }

    public function test_that_it_resolves_route_from_a_closure()
    {
        $this->router->get('/users',fn()=>[1,2,3]);

        $this->assertSame([1,2,3],$this->router->resolve('/users',HttpMethod::GET->value));
    }

    public function test_that_it_resolves_route()
    {
        $users = new class() {
            public function index(): array
            {
                return [1,2,3];
            }
        };

        $this->router->get('/users',[$users::class,'index']);

        $this->assertSame([1,2,3],$this->router->resolve('/users',HttpMethod::GET->value));

    }
}