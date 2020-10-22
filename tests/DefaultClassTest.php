<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Fountain\UninstantiableException;

final class DefaultClassTest extends TestCase
{

    public function testNoDefaultOfClass()
    {
        $fountain = new \Falgun\Fountain\Fountain();

        $dependentA = $fountain->get(DependentA1::class);

        $this->assertInstanceOf(DependencyB::class, $dependentA->b);
    }

    public function testNullAsDefaultOfClass()
    {
        $fountain = new \Falgun\Fountain\Fountain();

        $dependentB = $fountain->get(DependentB1::class);

        $this->assertInstanceOf(DependencyB::class, $dependentB->b);
    }

    public function testNullAsDefaultOfInterface()
    {
        $fountain = new \Falgun\Fountain\Fountain();

        $dependentC = $fountain->get(DependentC1::class);

        $this->assertSame(null, $dependentC->iA);
        $this->assertSame(null, $dependentC->tA);
        $this->assertSame('string', $dependentC->sA);
    }

    public function testNoDefaultOfInterface()
    {
        $fountain = new \Falgun\Fountain\Fountain();

        try {
            $fountain->get(DependentC2::class);
        } catch (UninstantiableException $ex) {
            $this->assertSame(<<<TEXT
                Falgun\Fountain\Tests\InterfaceA1 is Uninstantiable! And, No default value for \$iA of Falgun\Fountain\Tests\DependentC2->__construct() found!
                TEXT,
                $ex->getMessage());
            $this->assertSame(500, $ex->getCode());
        }
    }
}

final class DependentA1
{

    public DependencyA $a;
    public ?DependencyB $b;

    public function __construct(DependencyA $a, ?DependencyB $b)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

final class DependentB1
{

    public DependencyA $a;
    public ?DependencyB $b;

    public function __construct(DependencyA $a, DependencyB $b = null)
    {
        $this->a = $a;
        $this->b = $b;
    }
}

final class DependentC1
{

    public ?InterfaceA1 $iA;
    public ?TraitA1 $tA;
    public $sA;

    public function __construct(InterfaceA1 $iA = null, TraitA1 $tA = null, $sA = 'string')
    {
        $this->iA = $iA;
        $this->tA = $tA;
        $this->sA = $sA;
    }
}

final class DependentC2
{

    public InterfaceA1 $iA;

    public function __construct(InterfaceA1 $iA)
    {
        $this->iA = $iA;
    }
}

final class DependencyA
{
    
}

final class DependencyB
{
    
}

interface InterfaceA1
{
    
}

trait TraitA1
{
    
}
