<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests\Stubs;

final class DepA
{

    public DepB $b;
    public DepAB $ab;

//    private Interface1 $c;

    public function __construct(
        DepB $b,
        DepAB $ab
//        Interface1 $c = null
    )
    {
        $this->b = $b;
        $this->ab = $ab;
//        $this->c = $c ?? new DepC();
    }
}
