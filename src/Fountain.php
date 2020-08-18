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
class Fountain implements ContainerInterface
{

    protected SharedServices $container;
    protected DependencyParser $parser;

    public function __construct(SharedServices $container)
    {
        $this->container = $container;
        $this->parser = new DependencyParser($container);
    }

    public function get(string $id)
    {
        if ($this->container->has($id)) {
            return $this->container->get($id);
        }

        $object = $this->parser->resolve($id);

        $this->set($id, $object);

        return $object;
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    public function set(string $id, $object): void
    {
        $this->container->set($id, $object);
    }
}
