<?php

declare(strict_types=1);

namespace Peak\Pipeline;

interface PipeInterface
{
    /**
     * @param mixed $payload
     * @return mixed
     */
    public function __invoke($payload);
}
