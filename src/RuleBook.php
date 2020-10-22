<?php
declare(strict_types=1);

namespace Falgun\Fountain;

final class RuleBook
{

    private array $rules;

    public function __construct(array $rules = [])
    {
        $this->rules = $rules;
    }

    public function isShareable(string $id): bool
    {
        return isset($this->rules['shared'][$id]);
    }

    public function hasImplementation(string $id): bool
    {
        return isset($this->rules['implementations'][$id]);
    }

    public function getImplementation(string $id): string
    {
        return $this->rules['implementations'][$id];
    }
}
