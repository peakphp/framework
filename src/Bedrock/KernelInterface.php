<?php

declare(strict_types=1);

namespace Peak\Bedrock;

use Psr\Container\ContainerInterface;

/**
 * Interface KernelInterface
 * @package Peak\Bedrock
 */
interface KernelInterface
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
