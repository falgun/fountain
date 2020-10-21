<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests;

use Falgun\Fountain\Fountain;
use PHPUnit\Framework\TestCase;
use Falgun\Fountain\SharedContainer;

final class DefaultScalarTest extends TestCase
{

    public function testDefaultScalar()
    {
        $fountain = new Fountain();

        $abc = $fountain->get(StubDefaultScalar::class);

        $this->assertSame([
            'str' => 'default',
            'str2' => '',
            'number' => 1,
            'number2' => 2,
            'decimal' => 1.2,
            'decimal2' => 2.3,
            'multi' => [
                0 => 'a',
            ],
            'multi2' => [
                0 => 'a',
                1 => 'b',
            ],
            'isTrue' => true,
            'isFalse' => false,
            ],
            $abc->values);
    }

    public function testNoDefaultScalar()
    {
        $fountain = new Fountain();

        try {
            $fountain->get(StubNoDefault::class);
            $this->fail();
        } catch (\InvalidArgumentException $ex) {
            $this->assertSame('No default value for noDefault found !', $ex->getMessage());
            $this->assertSame(0, $ex->getCode());
        }
    }
}

class StubDefaultScalar
{

    public array $values;

    public function __construct(
        string $str = 'default',
        $str2 = '',
        int $number = 1,
        $number2 = 2,
        float $decimal = 1.2,
        $decimal2 = 2.3,
        array $multi = ['a'],
        $multi2 = ['a', 'b'],
        bool $isTrue = true,
        $isFalse = false
    )
    {
        $this->values = [
            'str' => $str,
            'str2' => $str2,
            'number' => $number,
            'number2' => $number2,
            'decimal' => $decimal,
            'decimal2' => $decimal2,
            'multi' => $multi,
            'multi2' => $multi2,
            'isTrue' => $isTrue,
            'isFalse' => $isFalse,
        ];
    }
}

class StubNoDefault
{

    public function __construct(string $noDefault)
    {
        
    }
}
