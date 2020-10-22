<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use Falgun\Fountain\RuleBook;
use Falgun\Fountain\Fountain;
use PHPUnit\Framework\TestCase;
use Falgun\Fountain\SharedContainer;
use Falgun\Fountain\UninstantiableException;

final class ImplementationRuleBookTest extends TestCase
{

    public function testNonImplementationRule()
    {
        $ruleBook = new RuleBook();
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $this->expectException(UninstantiableException::class);
        $fountain->get(Stubs\DepD::class);
    }

    public function testHasImplementationRule()
    {
        $ruleBook = new RuleBook([
            'implementations' => [
                Stubs\Interface1::class => Stubs\DepC::class,
            ]
        ]);
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $depD = $fountain->get(Stubs\DepD::class);

        $this->assertInstanceOf(Stubs\DepC::class, $depD->interface1);
    }

    public function testHasImplementationOfExtendedClass()
    {
        $ruleBook = new RuleBook([
            'implementations' => [
                Stubs\Interface1::class => Stubs\DepC::class,
                Stubs\DepAB::class => Stubs\DepE::class,
            ]
        ]);
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $depA1 = $fountain->get(Stubs\DepA::class);

        $this->assertInstanceOf(Stubs\DepE::class, $depA1->ab);
    }

    public function testHasInvalidImplementationRule()
    {
        $ruleBook = new RuleBook([
            'implementations' => [
                Stubs\Interface1::class => Stubs\DepAB::class,
            ]
        ]);
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $this->expectException(\TypeError::class);
        $fountain->get(Stubs\DepD::class);
    }
}
