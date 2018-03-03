<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use Peak\Config\Exceptions\ProcessorException;

class ArrayProcessor extends AbstractProcessor
{
    /**
     * Array processor
     * @throws ProcessorException
     */
    public function process($data)
    {
        if (!is_array($data)) {
            throw new ProcessorException(__CLASS__.' expects data to be an array. '.gettype($data).' given.');
        }

        $this->content = $data;
    }
}
