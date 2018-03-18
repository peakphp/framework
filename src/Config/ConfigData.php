<?php

declare(strict_types=1);

namespace Peak\Config;

class ConfigData
{
    /**
     * @var array
     */
    protected $processed_content;

    /**
     * ConfigData constructor.
     *
     * @param mixed $data
     * @param ProcessorInterface $processor
     */
    public function __construct($data, ProcessorInterface $processor)
    {
        $processor->process($data);
        $this->processed_content = $processor->getContent();
    }

    /**
     * Get config file processed content
     *
     * @return array
     */
    public function get(): array
    {
        return $this->processed_content;
    }
}
