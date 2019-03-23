<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;
use Peak\Config\Exception\ProcessorTypeException;

use function is_array;
use function is_callable;

class CallableProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorException
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!is_callable($data)) {
            throw new ProcessorTypeException(__CLASS__, 'callable', $data);
        }

        $content = $data();

        if (!is_array($content)) {
            throw new ProcessorException(__CLASS__.' expects callable to return an array');
        }

        return $content;
    }
}
