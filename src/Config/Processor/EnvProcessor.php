<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

use function error_get_last;
use function parse_ini_string;

class EnvProcessor implements ResourceProcessor
{
    /**
     * @throws ProcessorException
     */
    public function process($data): array
    {
        // we silence error(s) so we can catch them and throw a proper exception after
        $data = @parse_ini_string($data, false, INI_SCANNER_RAW);

        // fail if there was an error while processing the specified ini file
        if ($data === false) {
            $error = error_get_last();
            throw new ProcessorException(__CLASS__.' fail to parse data: '.$error['message']);
        }
        return $data;
    }
}
