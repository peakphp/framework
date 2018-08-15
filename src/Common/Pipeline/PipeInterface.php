<?php

declare(strict_types=1);

namespace Peak\Common\Pipeline;

/**
 * Interface PipeInterface
 * @package Peak\Pipelines
 */
interface PipeInterface
{
    /**
     * @param mixed $payload
     * @return mixed
     */
    public function __invoke($payload);
}
