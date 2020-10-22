<?php
declare(strict_types=1);

namespace Falgun\Fountain\Tests\Stubs;

final class DepD
{

    public Interface1 $interface1;

    public function __construct(Interface1 $interface1)
    {
        $this->interface1 = $interface1;
    }
}
