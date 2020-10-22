<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests\Stubs;

final class DepB
{

    public DepAB $ab;

    public function __construct(DepAB $ab)
    {
        $this->ab = $ab;
    }
}
