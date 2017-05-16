<?php

namespace Peak\Pipelines;

use Peak\Pipelines\Pipeline;
use Peak\Pipelines\PipeInterface;
use Peak\Di\ContainerInterface;

use \Closure;
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
        if($pipe instanceof Closure) {
            // for closure
            return call_user_func($pipe, $payload);
        } elseif (is_callable($pipe)) {
            return $this->processPipeInstance($pipe, $payload);
        } elseif (is_string($pipe) && class_exists($pipe)) {
            // class string with or without di
            $pinst = (isset($this->container)) ? $this->container->instantiate($pipe) : new $pipe();
            if (!$pinst instanceof PipeInterface) {
                throw new Exception('Pipe "'.$pipe.'" must implements PipeInterface');
            }
            return $this->processPipeInstance($pinst, $payload);
        } elseif ($pipe instanceof Pipeline) {
            // another pipeline instance
            return $pipe->process($payload);
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
