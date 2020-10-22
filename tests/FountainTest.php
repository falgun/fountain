<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Fountain\Fountain;
use Falgun\Fountain\SharedContainer;
use Falgun\Fountain\DependencyParser;
use Falgun\Fountain\UninstantiableException;

final class FountainTest extends TestCase
{

    public function testFountain(): void
    {
        $fountain = new Fountain();

        $depA = $fountain->get(Stubs\DepA::class);

//        var_dump($depA);die;
        $this->assertInstanceOf(Stubs\DepA::class, $depA);
    }

    public function testNonExistantClass()
    {
        $fountain = new Fountain();

        try {
            $fountain->get(ClassDoesNotExists::class);
            $this->fail();
        } catch (\ReflectionException $ex) {
            $this->assertSame('Class Falgun\Fountain\Tests\ClassDoesNotExists does not exist', $ex->getMessage());
            $this->assertSame(-1, $ex->getCode());
        }
    }

    public function testNonInstantiable()
    {
        $fountain = new Fountain();

        try {
            $fountain->get(Stubs\Interface1::class);
            $this->fail();
        } catch (UninstantiableException $ex) {
            $this->assertSame(Stubs\Interface1::class . ' is Uninstantiable!', $ex->getMessage());
            $this->assertSame(500, $ex->getCode());
        }
    }

    public function testClassWithoutDependency()
    {
        $share = new SharedContainer();
        $parser = new DependencyParser($share);

        $obj = $parser->resolve(ClassWithoutDependency::class);

        $this->assertInstanceOf(ClassWithoutDependency::class, $obj);
    }

    public function testClassWithoutCtor()
    {
        $share = new SharedContainer();
        $parser = new DependencyParser($share);

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
