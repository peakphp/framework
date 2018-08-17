<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Config\Exception\ProcessorException;

/**
 * Class EnvProcessor
 * @package Peak\Config\Processor
 */
class EnvProcessor implements ProcessorInterface
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
