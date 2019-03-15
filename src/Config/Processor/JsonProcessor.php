<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

use function json_decode;
use function json_last_error;
use function json_last_error_msg;

class JsonProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorException
     */
    public function process($data): array
    {
        // decode json
        $content = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ProcessorException(__CLASS__.': error while decoding json > '.json_last_error_msg());
        }

        return $content;
    }
}
