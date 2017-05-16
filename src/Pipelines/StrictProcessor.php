<?php

namespace Peak\Pipelines;

use Peak\Pipelines\AbstractProcessor;
use Peak\Pipelines\ProcessorInterface;

class StrictProcessor extends AbstractProcessor implements ProcessorInterface
{
    /**
     * Verification closure
     * @var callable
     */
    protected $check;

    /**
     * Constructor
     *
     * @param callable                $check closure returning a boolean
     * @param ContainerInterface|null $container Di container to resolve pipe class
     */
    public function __construct(callable $check, ContainerInterface $container = null)
    {
        $this->check = $check;
        $this->container = $container;
    }

    /**
     * Process pipes
     *
     * @param  array  $pipes
     * @param  mixed $payload
     * @return mixed
     */
    public function process(array $pipes, $payload)
    {
        foreach ($pipes as $pipe) {
            $payload = $this->resolvePipe($pipe, $payload);

            // if check fail, break the pipeline right here and return the payload
            if (call_user_func($this->check, $payload) === false) {
                return $payload;
            }
        }

        return $payload;
    }
}
