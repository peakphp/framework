<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use Peak\Config\Exceptions\ProcessorException;

class CallableProcessor extends AbstractProcessor
{
    /**
     * Process
     * @throws ProcessorException
     */
    public function process($data)
    {
        if (!is_callable($data)) {
            throw new ProcessorException(__CLASS__.' expects data to be callable. '.gettype($data).' given.');
        }

        $this->content = $data();

        if (!is_array($this->content)) {
            throw new ProcessorException(__CLASS__.' expects callable data to return an array');
        }
    }
}
