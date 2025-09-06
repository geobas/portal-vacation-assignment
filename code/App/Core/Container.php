<?php

declare(strict_types=1);

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionNamedType;

class Container
{
    /**
     * @var array<class-string, class-string|callable(self):object>
     */
    private array $bindings = [];

    /**
     * Bind an abstract type to a concrete implementation.
     *
     * @param class-string $abstract
     * @param class-string|callable(self):object $concrete
     */
    public function set(string $abstract, callable|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    /**
     * Resolve an instance from the container.
     *
     * @template T of object
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id): object
    {
        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];

            if (is_callable($concrete)) {
                $instance = $concrete($this);

                /** @var T $instance */
                return $instance;
            }

            // recursive resolution goes to non-generic helper
            return $this->resolveBinding($concrete);
        }

        $resolved = $this->resolve($id);

        /** @var T $resolved */
        return $resolved;
    }

    /**
     * Resolve a binding (non-generic), used for recursion.
     *
     * @param class-string $class
     * @return object
     */
    private function resolveBinding(string $class): object
    {
        // Simply call resolve() which handles constructor dependencies
        return $this->resolve($class);
    }

    /**
     * Instantiate a class with resolved dependencies.
     *
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    private function resolve(string $class): object
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if ($constructor === null) {
            return new $class();
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                /** @var object $dependency */
                $dependency = $this->get($type->getName());
                $params[] = $dependency;
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency {$param->getName()} for class {$class}");
            }
        }

        return $reflector->newInstanceArgs($params);
    }
}
