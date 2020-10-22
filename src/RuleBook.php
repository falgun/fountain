<?php
declare(strict_types=1);

namespace Falgun\Fountain;

final class RuleBook
{

    /** @var array<string, array<string,mixed>> */
    private array $rules;

    /**
     * @param array<string, array<string,mixed>> $rules
     */
    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    /**
     * @param class-string $id
     * @return bool
     */
    public function isShareable(string $id): bool
    {
        return isset($this->rules['shared'][$id]);
    }

    /**
     * @param class-string $id
     * @return bool
     */
    public function hasImplementation(string $id): bool
    {
        return isset($this->rules['implementations'][$id]);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return class-string<T>
     */
    public function getImplementation(string $id): string
    {
        return $this->rules['implementations'][$id];
    }
}
