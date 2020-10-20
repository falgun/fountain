<?php
declare(strict_types=1);

namespace Falgun\Fountain;

final class SharedContainer implements SharedContainerInterface
{

    /** @var array<class-string, mixed> */
    private array $shared;

    /**
     * @param array<class-string, mixed> $shared
     */
    public function __construct(array $shared = [])
    {
        $this->shared = $shared;
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id)
    {
        return $this->shared[$id];
    }

    /**
     * @param class-string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->shared[$id]);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @param T $object
     * @return void
     */
    public function set(string $id, $object): void
    {
        $this->shared[$id] = $object;
    }
}
