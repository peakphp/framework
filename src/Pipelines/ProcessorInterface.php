<?php

namespace Peak\Pipelines;

/**
 * Interface ProcessorInterface
 */
interface ProcessorInterface
{
    /**
     * Process pipes
     *
     * @param array $pipes
     * @param mixed $payload
     * @return mixed
     */
    public function process(array $pipes, $payload);
}
