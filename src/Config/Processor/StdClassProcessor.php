<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorTypeException;

use function json_decode;
use function json_encode;

class StdClassProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!$data instanceof \stdClass) {
            throw new ProcessorTypeException(__CLASS__, 'stdClass', $data);
        }
        // @todo handle possibility of json_* returning false
        return json_decode(json_encode($data), true);
    }
}
