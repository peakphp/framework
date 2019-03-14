<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

use Peak\Blueprint\Common\Initializable;
use Psr\Container\ContainerInterface;

interface Kernel extends Initializable
{
    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @return string
     */
    public function getEnv(): string;
}
