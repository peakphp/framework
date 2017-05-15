<?php

namespace Peak\Pipelines;

use Peak\Pipelines\AbstractProcessor;
use Peak\Pipelines\ProcessorInterface;

class DefaultProcessor extends AbstractProcessor implements ProcessorInterface
{
    public function process(array $pipes, $payload)
    {
        foreach ($pipes as $pipe) {
            $payload = $this->resolvePipe($pipe, $payload);
        }

        return $payload;
    }
}
