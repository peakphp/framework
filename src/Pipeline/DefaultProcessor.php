<?php

declare(strict_types=1);

namespace Peak\Pipeline;

/**
 * Class DefaultProcessor
 * @package Peak\Pipelines
 */
class DefaultProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * Process pipes
     *
     * @param array $pipes
     * @param mixed $payload
     * @return mixed
     * @throws Exception\InvalidPipeException
     * @throws Exception\MissingPipeInterfaceException
     */
    public function process(array $pipes, $payload)
    {
        foreach ($pipes as $pipe) {
            $payload = $this->resolvePipe($pipe, $payload);
        }

        return $payload;
    }
}
