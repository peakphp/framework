<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

/**
 * Class StdClassProcessor
 * @package Peak\Config\Processor
 */
class StdClassProcessor implements ResourceProcessor
{
    /**
     * @param \stdClass $data
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
