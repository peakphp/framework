<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Config\Processor\ProcessorInterface;

class DataStream implements StreamInterface
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var ProcessorInterface
     */
    private $processor;

    /**
     * DataStream constructor
     *
     * @param mixed $data
     * @param ProcessorInterface $handlers
     */
    public function __construct($data, ProcessorInterface $processor)
    {
        $this->data = $data;
        $this->processor = $processor;
    }

    /**
     * @param mixed $data
     * @return array
     */
    public function get(): array
    {
        return $this->processor->process($this->data);
    }
}
