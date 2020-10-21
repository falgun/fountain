<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Fountain\Fountain;
use Falgun\Fountain\SharedContainer;
use Falgun\Fountain\DependencyParser;

final class FountainTest extends TestCase
{

    public function testFountain(): void
    {
        $fountain = new Fountain();

        $depA = $fountain->get(Stubs\DepA::class);

//        var_dump($depA);die;
        $this->assertInstanceOf(Stubs\DepA::class, $depA);
    }

    public function testNonInstaciable()
    {
        $fountain = new Fountain();

        try {
            $fountain->get(Stubs\Interface1::class);
            $this->fail();
        } catch (\InvalidArgumentException $ex) {
            $this->assertSame('Cannot instantiate ' . Stubs\Interface1::class, $ex->getMessage());
            $this->assertSame(0, $ex->getCode());
        }
    }

    public function testClassWithoutDependency()
    {
        $parser = new DependencyParser();

        $obj = $parser->resolve(ClassWithoutDependency::class);

        $this->assertInstanceOf(ClassWithoutDependency::class, $obj);
    }

    public function testClassWithoutCtor()
    {
        $parser = new DependencyParser();

        $obj = $parser->resolve(ClassWithoutCtor::class);

        $this->assertInstanceOf(ClassWithoutCtor::class, $obj);
    }
}

final class ClassWithoutDependency
{

    public function __construct()
    {
        
    }
}

final class ClassWithoutCtor
{
    
}
