<?php

declare(strict_types=1);

namespace Peak\Bedrock\Bootstrap;

use Peak\Blueprint\Common\Bootable;

/**
 * Class CallableProcess
 * @package Peak\Bedrock\Bootstrap
 */
class CallableProcess implements Bootable
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * CallableProcess constructor.
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @return mixed|void
     */
    public function boot()
    {
        $fn = $this->callable;
        $fn();
    }
}
