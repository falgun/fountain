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

    public function get(string $key)
    {
        return $this->shared[$key];
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->shared);
    }

    public function set(string $key, $service)
    {
        $this->shared[$key] = $service;
        
        return $service;
    }
}
