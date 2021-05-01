<?php
declare(strict_types=1);

namespace Falgun\Fountain;

use ReflectionClass;
use ReflectionMethod;
use ReflectionParameter;

final class DependencyParser
{

    private SharedContainerInterface $shared;
    private RuleBook $ruleBook;

    public function __construct(SharedContainerInterface $shared, RuleBook $ruleBook)
    {
        $this->shared = $shared;
        $this->ruleBook = $ruleBook;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    public function resolve(string $class)
    {
        if ($this->ruleBook->hasImplementation($class)) {
            $class = $this->ruleBook->getImplementation($class);
        }

        if ($this->shared->has($class)) {
            return $this->shared->get($class);
        }

        $resolved = $this->resolveClassWithReflection($class);

        if ($this->ruleBook->isShareable($class)) {
            $this->shared->set($class, $resolved);
        }

        return $resolved;
    }

    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     * @throws \InvalidArgumentException
     */
    private function resolveClassWithReflection(string $class)
    {
        $reflection = new ReflectionClass($class);

        //is it instatiable ?
        if ($reflection->isInstantiable() === false) {
            throw UninstantiableException::fromClassName($class);
        }

        //check constructor
        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return $reflection->newInstanceWithoutConstructor();
        }

        $dependencies = $this->getConstructorDependencies($constructor);

        return $reflection->newInstanceArgs($dependencies);
    }

    private function getConstructorDependencies(ReflectionMethod $constructor): array
    {
        $parameters = $constructor->getParameters();
        return $this->getDependencies($parameters);
    }

    /**
     * @param array<int, ReflectionParameter> $parameters
     * @return array<int, mixed>
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
     * @return mixed
     *
     * @psalm-suppress UndefinedMethod
     */
    private function prepareDependency(ReflectionParameter $parameter)
    {
        $dependency = ($parameter->getType() && $parameter->getType()->isBuiltin() === false)
                        ? new ReflectionClass($parameter->getType()->getName())
                        : null;

        if ($dependency === null) {
            return $this->getDefaultValue($parameter);
        }

        $name = $dependency->getName();

        try {
            return $this->resolve($name);
        } catch (UninstantiableException $exception) {
            return $this->tryFallbackToDefaultValue($parameter, $exception);
        }
    }

    /**
     * @param ReflectionParameter $parameter
     * @param UninstantiableException $uninstantiableException
     * @return mixed
     * @throws UninstantiableException
     */
    private function tryFallbackToDefaultValue(ReflectionParameter $parameter, UninstantiableException $uninstantiableException)
    {
        try {
            return $this->getDefaultValue($parameter);
        } catch (\InvalidArgumentException $exception) {
            throw UninstantiableException::fallbackToDefaultValueFailed($uninstantiableException, $exception);
        }
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

        $declaringClassName = $this->getDeclaringClassName($parameter);

        throw new \InvalidArgumentException(<<<TEXT
            No default value for \${$parameter->getName()} of {$declaringClassName}->__construct() found!
            TEXT);
    }

    private function getDeclaringClassName(ReflectionParameter $parameter): string
    {
        $declaringClass = $parameter->getDeclaringClass();

        if ($declaringClass instanceof ReflectionClass) {
            return $declaringClass->getName();
        }

        return 'Unknown';
    }
}
