<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

/**
 * Class ArrayProcessor
 * @package Peak\Config\Processor
 */
class ArrayProcessor implements ResourceProcessor
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
