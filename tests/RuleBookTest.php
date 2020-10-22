<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use Falgun\Fountain\RuleBook;
use Falgun\Fountain\Fountain;
use PHPUnit\Framework\TestCase;
use Falgun\Fountain\SharedContainer;

final class RuleBookTest extends TestCase
{

    public function testNonSharedRule()
    {
        $ruleBook = new RuleBook();
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $depA1 = $fountain->get(Stubs\DepA::class);

        $depA1->id = 1;

        $depA2 = $fountain->get(Stubs\DepA::class);

        $this->assertNotSame($depA1, $depA2);
    }

    public function testSharedRule()
    {
        $ruleBook = new RuleBook(['shared' => [Stubs\DepA::class => true]]);
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $depA1 = $fountain->get(Stubs\DepA::class);

        $depA1->id = 1;

        $depA2 = $fountain->get(Stubs\DepA::class);

        $this->assertSame($depA1, $depA2);
    }

    public function testSharedRuleOfNestedDependency()
    {
        $rules = [
            'shared' => [
                Stubs\DepAB::class => true,
                Stubs\DepA::class => true,
            ]
        ];
        $ruleBook = new RuleBook($rules);
        $fountain = new Fountain(new SharedContainer, $ruleBook);

        $depA1 = $fountain->get(Stubs\DepA::class);

        $depA1->ab->id = 2;

        $depA2 = $fountain->get(Stubs\DepA::class);

        $this->assertSame($depA1->ab, $depA2->b->ab);
    }
}
