<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

use function gettype;
use function is_array;
use function is_callable;

class CallableProcessor implements ResourceProcessor
{
    /**
     * Process
     * @throws ProcessorException
     */
    public function process($data): array
    {
        if (!is_callable($data)) {
            throw new ProcessorException(__CLASS__.' expects data to be callable. '.gettype($data).' given.');
        }

        $content = $data();

        if (!is_array($content)) {
            throw new ProcessorException(__CLASS__.' expects callable data to return an array');
        }

        return $content;
    }
}
