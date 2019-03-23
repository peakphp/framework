<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorTypeException;

use function is_array;

class ArrayProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!is_array($data)) {
            throw new ProcessorTypeException(__CLASS__, 'array', $data);
        }
        return $data;
    }
}
