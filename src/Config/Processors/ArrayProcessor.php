<?php

declare(strict_types=1);

namespace Peak\Config\Processors;

use Peak\Config\Exceptions\ProcessorException;
use Peak\Config\ProcessorInterface;

class ArrayProcessor implements ProcessorInterface
{
    /**
     * Array processor
     * @throws ProcessorException
     */
    public function process($data): array
    {
        if (!is_array($data)) {
            throw new ProcessorException(__CLASS__.' expects data to be an array. '.gettype($data).' given.');
        }

        return $data;
    }
}
