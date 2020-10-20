<?php

namespace Falgun\Fountain;

interface ContainerInterface
{

    /**
     * @template T
     * @psalm-param class-string<T> $id
     * @return T
     */
    public function get(string $id);

    /**
     * @param class-string $id
     * @return bool
     */
    public function has(string $id): bool;

    /**
     * @template T
     * @psalm-param class-string<T> $id
     * @psalm-param T $object
     * @return void
     */
    public function set(string $id, $object): void;
}
