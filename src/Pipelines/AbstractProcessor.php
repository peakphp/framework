<?php

namespace Peak\Pipelines;

use Peak\Pipelines\Exceptions\InvalidPipeException;
use Peak\Pipelines\Exceptions\MissingPipeInterfaceException;
use Psr\Container\ContainerInterface;
use \Closure;

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
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Resolve a pipe execution
     *
     * @param  mixed $pipe     Support closure, class instance, class string, pipeline instance and pipe function name
     * @param  mixed $payload  Payload pass to each pipe
     * @return mixed
     * @throws MissingPipeInterfaceException
     * @throws InvalidPipeException
     */
    protected function resolvePipe($pipe, $payload)
    {
        if ($pipe instanceof Closure) {
            // process pipe closure
            return call_user_func($pipe, $payload);
        } elseif ($pipe instanceof Pipeline) {
            // process another pipeline instance
            return $pipe->process($payload);
        } elseif (is_string($pipe) && class_exists($pipe)) {
            // process pipe class name
            return $this->processPipeClassName($pipe, $payload);
        } elseif(is_object($pipe)) {
            // process pipe object instance
            return $this->processPipeObject($pipe, $payload);
        } elseif (is_callable($pipe)) {
            // process callable pipe function
            return $pipe($payload);
        }

        // unknown pipe type
        throw new InvalidPipeException();
    }

    /**
     * Process Pipe string class name
     *
     * @param string $pipe
     * @param mixed $payload
     * @return mixed
     * @throws MissingPipeInterfaceException
     */
    protected function processPipeClassName($pipe, $payload)
    {
        $pinst = (isset($this->container)) ? $this->container->create($pipe) : new $pipe();

        if (!$pinst instanceof PipeInterface) {
            throw new MissingPipeInterfaceException(get_class($pinst));
        }
        return $pinst($payload);
    }

    /**
     * Process pipe class object
     *
     * @param object $pipe
     * @param mixed $payload
     * @return mixed
     * @throws MissingPipeInterfaceException
     */
    protected function processPipeObject($pipe, $payload)
    {
        if (!$pipe instanceof PipeInterface) {
            throw new MissingPipeInterfaceException(get_class($pipe));
        }
        return $pipe($payload);
    }

}
