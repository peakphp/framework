<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Blueprint\Config\Config;
use Peak\Config\Exception\ProcessorTypeException;

class ConfigProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!$data instanceof Config) {
            throw new ProcessorTypeException(__CLASS__, Config::class, $data);
        }

        return $data->toArray();
    }
}
