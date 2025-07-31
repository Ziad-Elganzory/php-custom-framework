<?php

declare(strict_types=1);

namespace App;

use App\Attributes\Route;
use App\Exceptions\RouteNotFoundException;
use ReflectionAttribute;
use ReflectionClass;

class Router
{
    private array $routes = [];

    public function __construct(private Container $container){}

    public function registerRouteFromControllerAttribute(array $controllers): void
    {
        foreach ($controllers as $controller)
        {
            $reflectionController = new ReflectionClass($controller);
            foreach($reflectionController->getMethods() as $method)
            {
                $attributes = $method->getAttributes(Route::class, ReflectionAttribute::IS_INSTANCEOF);

                foreach($attributes as $attribute)
                {
                    $route = $attribute->newInstance();

                    $this->register(
                        $route->method->value,
                        $route->routePath,
                        [$controller, $method->getName()]
                    );
                }
            }
        }
    }
    public function register(string $requestMethod,string $route, callable|array $action):self
    {
        $this->routes[$requestMethod][$route] = $action;
        return $this;
    }

    public function get(string $route, callable|array $action): self
    {
        return $this->register('get',$route, $action);
    }

    public function post(string $route, callable|array $action): self
    {
        return $this->register('post',$route, $action);
    }

    public function routes(): array
    {
        return $this->routes;
    }

    public function resolve(string $uri, string $requestMethod)
    {
        $route = explode("?", $uri)[0];
        $route = $route === '/' ? $route : rtrim($route, '/');
        $routes = $this->routes[$requestMethod] ?? [];

        if (isset($routes[$route])) {
            $action = $routes[$route];
            if (is_callable($action)) {
                return call_user_func($action);
            }
            if (is_array($action)) {
                [$class, $method] = $action;
                if (class_exists($class)) {
                    $class = $this->container->get($class);
                    if (method_exists($class, $method)) {
                        return call_user_func([$class, $method]);
                    }
                }
            }
        }

        foreach ($routes as $routePattern => $action) {
            $pattern = preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', $routePattern);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $route, $matches)) {
                // Extract only named parameters
                $params = array_filter(
                    $matches,
                    fn($key) => !is_int($key),
                    ARRAY_FILTER_USE_KEY
                );
                    if (is_callable($action)) {
                        return call_user_func($action, $params);
                    }
                    if (is_array($action)) {
                        [$class, $method] = $action;
                        if (class_exists($class)) {
                            $class = $this->container->get($class);
                            if (method_exists($class, $method)) {
                                return call_user_func([$class, $method], $params);
                            }
                        }
                    }
            }
        }
        throw new RouteNotFoundException();
    }
}