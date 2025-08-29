<?php

declare(strict_types=1);

namespace App\Core;

use ReflectionClass;
use ReflectionNamedType;

class Container
{
    private array $bindings = [];

    public function set(string $abstract, callable|string $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function get(string $id): object
    {
        if (isset($this->bindings[$id])) {
            $concrete = $this->bindings[$id];

            // If it’s a callable, execute it
            if (is_callable($concrete)) {
                return $concrete($this);
            }

            // If it’s a class name, resolve it
            if (is_string($concrete)) {
                return $this->get($concrete);
            }
        }

        return $this->resolve($id);
    }

    private function resolve(string $class): object
    {
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new \Exception("Class {$class} is not instantiable.");
        }

        $constructor = $reflector->getConstructor();
        if (!$constructor) {
            return new $class;
        }

        $params = [];
        foreach ($constructor->getParameters() as $param) {
            $type = $param->getType();
            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $params[] = $this->get($type->getName());
            } elseif ($param->isDefaultValueAvailable()) {
                $params[] = $param->getDefaultValue();
            } else {
                throw new \Exception("Can’t resolve dependency {$param->getName()} for class {$class}");
            }
        }

        return $reflector->newInstanceArgs($params);
    }
}
