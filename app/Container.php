<?php

namespace App;

use App\Exceptions\Container\ContainerException;
use App\Exceptions\Container\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionUnionType;
use function PHPUnit\Framework\isInstanceOf;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if($this->has($id))
        {
            $entry = $this->entries[$id];

            return $entry($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $concrete)
    {
        $this->entries[$id] = $concrete;
    }

    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);

        if(!$reflectionClass->isInstantiable())
        {
            throw new ContainerException('Class "'. $id .'" is not instantiable');
        }

        $constructor = $reflectionClass->getConstructor();

        if( ! $constructor )
        {
            return new $id;
        }

        $constructorParams = $constructor->getParameters();

        if( ! $constructorParams )
        {
            return new $id;
        }

        $dependencies = array_map(
        function(\ReflectionParameter $param) use($id){
                    $name = $param->getName();
                    $type = $param->getType();

                    if(!$type)
                    {
                        throw new ContainerException(
                            'Failed to resolve class "'. $id . '" because param "'. $name .'" is missing a type hint'
                        );
                    }

                    if($type instanceof ReflectionUnionType)
                    {
                        throw new ContainerException(
                            'Failed to resolve class "'. $id . '" because of union type for param "'. $name .'"'
                        );
                    }

                    if($type instanceof ReflectionNamedType && ! $type->isBuiltin())
                    {
                        return $this->get($type->getName());
                    }

                    throw new ContainerException(
                        'Failed to resolve class "'. $id . '" because of union type for param "'. $name .'"'
                    );
        },
        $constructorParams
    );

    return $reflectionClass->newInstanceArgs($dependencies);


    }

}