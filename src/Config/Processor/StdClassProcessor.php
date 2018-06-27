<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Config\Exception\ProcessorException;

class StdClassProcessor implements ProcessorInterface
{
    /**
     * @param $data
     * @return array
     * @throws ProcessorException
     */
    public function process($data): array
    {
        if (!$data instanceof \stdClass) {
            throw new ProcessorException(__CLASS__.' require an instance of stdClass, '.gettype($data).' received ...');
        }

        return json_decode(json_encode($data), true);
    }
}
