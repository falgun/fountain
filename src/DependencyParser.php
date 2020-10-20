<?php
declare(strict_types=1);

namespace Falgun\Fountain;

use ReflectionClass;
use ReflectionParameter;

class DependencyParser
{

    protected SharedServices $shared;

    public function __construct(SharedServices $shared)
    {
        $this->shared = $shared;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    public function resolve(string $class)
    {
        $reflector = new ReflectionClass($class);

        //is it instatiable ?
        if ($reflector->isInstantiable() === false) {
            throw new \Exception($class . ' Cant be instatiate !');
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
    protected function getDependencies(array $parameters): array
    {
        $dependencies = [];

        if (empty($parameters)) {
            return [];
        }

        foreach ($parameters as $parameter) {
            $dependencies[] = $this->prepareDependency($parameter);
        }

        return $dependencies;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return object
     */
    protected function prepareDependency(ReflectionParameter $parameter)
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
     * @throws \Exception
     */
    protected function getDefaultValue(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        //return null;
        throw new \Exception('No default value for ' . $parameter->getName() . ' found !');
    }
}
