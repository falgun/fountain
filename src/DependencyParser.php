<?php
declare(strict_types=1);

namespace Falgun\Fountain;

use ReflectionClass;
use ReflectionParameter;

final class DependencyParser
{

    private SharedContainerInterface $shared;

    public function __construct(SharedContainerInterface $shared)
    {
        $this->shared = $shared;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     * @throws \InvalidArgumentException
     */
    public function resolve(string $class)
    {
        $reflector = new ReflectionClass($class);

        //is it instatiable ?
        if ($reflector->isInstantiable() === false) {
            throw new \InvalidArgumentException('Cannot instantiate ' . $class);
        }

        //check constructor
        $constructor = $reflector->getConstructor();

        if ($constructor !== null) {
            //Get constructor parameter and its dependencies

            $parameters = $constructor->getParameters();
            $dependencies = $this->getDependencies($parameters);
        } else {
            $dependencies = [];
        }

        //instantiate class
        $classInstance = $reflector->newInstanceArgs($dependencies);

        return $classInstance;
    }

    /**
     * @param array<int, ReflectionParameter> $parameters
     * @return array<int, object>
     */
    private function getDependencies(array $parameters): array
    {
        if (empty($parameters)) {
            return [];
        }

        $dependencies = [];

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->prepareDependency($parameter);
        }

        return $dependencies;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return object
     */
    private function prepareDependency(ReflectionParameter $parameter)
    {
        $dependency = $parameter->getClass();

        if ($dependency === null) {
            return $this->getDefaultValue($parameter);
        }

        $name = $dependency->getName();

        if ($this->shared->has($name)) {
            return $this->shared->get($name);
        }

        return $this->resolve($name);
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function getDefaultValue(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        //return null;
        throw new \InvalidArgumentException('No default value for ' . $parameter->getName() . ' found !');
    }
}
