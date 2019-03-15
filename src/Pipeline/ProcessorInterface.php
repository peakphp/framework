<?php

declare(strict_types=1);

namespace Peak\Pipeline;

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
