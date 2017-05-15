<?php

namespace Peak\Pipelines;

interface ProcessorInterface
{
    public function process(array $pipes, $payload);
}
