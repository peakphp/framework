<?php

declare(strict_types=1);

namespace Peak\Bedrock\Bootstrap;

use Peak\Blueprint\Common\Bootable;

class CallableProcess implements Bootable
{
    /**
     * @var callable 
     */
    private $callable;

    public function __construct(Callable $callable)
    {
        $this->callable = $callable;
    }

    public function boot(): void
    {
        $fn = $this->callable;
        $fn();
    }
}
