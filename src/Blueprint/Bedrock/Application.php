<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

use Peak\Blueprint\Collection\Dictionary;
use Psr\Container\ContainerInterface;

interface Application
{
    /**
     * @return Kernel
     */
    public function getKernel(): Kernel;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function getProp(string $property, $default = null);

    /**
     * @param string $property
     * @return bool
     */
    public function hasProp(string $property): bool;

    /**
     * Get application "properties" object
     * @return null|Dictionary
     */
    public function getProps(): ?Dictionary;
}
