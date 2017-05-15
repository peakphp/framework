<?php

namespace Peak\Pipelines;

use Peak\Pipelines\Pipeline;
use Peak\Pipelines\PipeInterface;
use Peak\Di\ContainerInterface;

use \Exception;

abstract class AbstractProcessor
{
    /**
     * Di container if any
     * @var ContainerInterface or null
     */
    protected $container = null;

    /**
     * Constructor
     * @param ContainerInterface|null $container Di container to resolve pipe class
     */
    public function __construct(ContainerInterface $container =  null)
    {
        $this->container = $container;
    }

    /**
     * Resolve a pipe execution
     *
     * @param  mixed $pipe     Support closure, class instance, class string or pipeline instance
     * @param  mixed $payload  Payload pass to each pipe
     * @return mixed
     */
    protected function resolvePipe($pipe, $payload)
    {
        if (is_callable($pipe)) {
            // for closure
            return call_user_func($pipe, $payload);
        } elseif ($pipe instanceof Pipeline) {
            // another pipeline instance
            return $pipe->process($payload);
        } elseif (class_exists($pipe)) {
            // class string with or without di
            if (isset($this->container)) {
                $pipe_instance = $this->container->instantiate($pipe);
            } else {
                $pipe_instance = new $pipe();
            }
            return $this->processPipeInstance($pipe_instance, $payload);
        }

        // unknow pipe type
        throw new Exception('Invalid pipe type');
    }

    /**
     * Process a pipe class
     *
     * @param  PipeInterface $pipe
     * @param  mixed         $payload
     * @return mixed
     */
    protected function processPipeInstance(PipeInterface $pipe, $payload)
    {
        return $pipe($payload);
    }
}
