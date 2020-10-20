<?php

namespace Falgun\Fountain;

interface ContainerInterface
{

    /**
     * @template T
     * @param class-string<T> $id
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
     * @param class-string<T> $id
     * @param T $object
     * @return void
     */
    public function set(string $id, $object): void;
}
