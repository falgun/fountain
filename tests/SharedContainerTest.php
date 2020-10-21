<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use Falgun\Fountain\Fountain;
use PHPUnit\Framework\TestCase;
use Falgun\Fountain\SharedContainer;

final class SharedContainerTest extends TestCase
{

    public function testSimpleGetSet()
    {
        $container = new SharedContainer();

        $this->assertFalse($container->has(SharedObjA::class));

        $container->set(SharedObjA::class, new SharedObjA);

        $this->assertTrue($container->has(SharedObjA::class));

        $sharedObjA = $container->get(SharedObjA::class);

        $this->assertInstanceOf(SharedObjA::class, $sharedObjA);
    }

    public function testSharedObjectWithFountain()
    {
        $fountain = new Fountain();

        $this->assertFalse($fountain->has(SharedObjA::class));

        $fountain->set(SharedObjA::class, new SharedObjA);

        $this->assertTrue($fountain->has(SharedObjA::class));

        $sharedObjA = $fountain->get(SharedObjA::class);

        $this->assertInstanceOf(SharedObjA::class, $sharedObjA);
    }

    public function testClassWithSharedDependency()
    {
        $sharedContainer = new SharedContainer();
        $sharedObjA = new SharedObjA();
        $sharedObjA->id = 2;
        $sharedContainer->set(SharedObjA::class, $sharedObjA);

        $fountain = new Fountain($sharedContainer);

        $dependentA = $fountain->get(DependentA::class);

        $this->assertSame(2, $dependentA->a->id);
    }
}

final class SharedObjA
{

    public int $id = 1;

}

final class DependentA
{

    public SharedObjA $a;

    public function __construct(SharedObjA $a)
    {
        $this->a = $a;
    }
}
