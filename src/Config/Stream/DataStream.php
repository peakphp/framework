<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Blueprint\Config\Stream;

class DataStream implements Stream
{
    /**
     * @var mixed
     */
    private $data;

    /**
     * @var ResourceProcessor
     */
    private $processor;

    /**
     * DataStream constructor.
     * @param mixed $data
     * @param ResourceProcessor $processor
     */
    public function __construct($data, ResourceProcessor $processor)
    {
        $this->data = $data;
        $this->processor = $processor;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        return $this->processor->process($this->data);
    }
}
