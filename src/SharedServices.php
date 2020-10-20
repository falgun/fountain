<?php
declare(strict_types=1);

namespace Falgun\Fountain;

class SharedServices
{

    protected array $shared;

    public function __construct()
    {
        $this->shared = [];
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return T
     */
    public function get(string $key)
    {
        return $this->shared[$key];
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->shared);
    }

    /**
     * @template T
     * @param class-string<T> $key
     * @param T $service
     * @return T
     */
    public function set(string $key, $service)
    {
        $this->shared[$key] = $service;

        return $service;
    }
}
