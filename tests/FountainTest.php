<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use PHPUnit\Framework\TestCase;
use Falgun\Fountain\Fountain;
use Falgun\Fountain\SharedContainer;

final class FountainTest extends TestCase
{
    public function testFountain():void
    {
        $shared = new SharedContainer();
        $fountain = new Fountain($shared);
        
        $depA = $fountain->get(Stubs\DepA::class);
        
//        var_dump($depA);die;
        $this->assertInstanceOf(Stubs\DepA::class, $depA);
    }
}
