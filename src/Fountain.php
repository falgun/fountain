<?php
declare(strict_types=1);

namespace Falgun\Fountain;

/**
 * This class has more usage
 * accept a config file and process it
 * services: is a array of service definition
 * shared: is flag for singleton classes
 * implementations: is an array of implementations of a interface
 */
final class Fountain implements ContainerInterface
{

    private SharedContainerInterface $shared;
    private DependencyParser $parser;
    private RuleBook $ruleBook;

    public function __construct(SharedContainerInterface $shared = null, RuleBook $ruleBook = null)
    {
        $this->shared = $shared ?? new SharedContainer();
        $this->ruleBook = $ruleBook ?? new RuleBook();
        $this->parser = new DependencyParser($this->shared, $this->ruleBook);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id)
    {
        return $this->parser->resolve($id);
    }

    /**
     * @param class-string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return $this->shared->has($id);
    }

    /**
     * @template T
     * @param class-string<T> $id
     * @param T $object
     * @return void
     */
    public function set(string $id, $object): void
    {
        $this->shared->set($id, $object);
    }
}
