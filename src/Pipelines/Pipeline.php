<?php

namespace Peak\Pipelines;

use Peak\Pipelines\DefaultProcessor;

class Pipeline
{
    /**
     * Pipes
     * @var array
     */
    protected $pipes = [];

    /**
     * Processor used by the pipeline
     * @var ProcessorInterface
     */
    protected $processor = null;

    /**
     * Constructor
     *
     * @param array                   $pipes
     * @param ProcessorInterface|null $processor
     */
    public function __construct(array $pipes = [], ProcessorInterface $processor = null)
    {
        $this->pipes = $pipes;
        $this->processor = $processor ?: new DefaultProcessor();
    }

    /**
     * Clone current pipeline, add the pipe to it and return the clone
     *
     * @param   mixed $pipe
     * @return  $this
     */
    public function add($pipe)
    {
        $pipeline = clone $this;
        $pipeline->pipes[] = $pipe;
        return $pipeline;
    }

    /**
     * Process the pipeline
     *
     * @param  mixed $payload
     * @return mixed
     */
    public function process($payload = null)
    {
        return $this->processor->process($this->pipes, $payload);
    }
}
