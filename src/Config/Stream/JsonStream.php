<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Config\Processor\JsonProcessor;

class JsonStream extends DataStream
{
    /**
     * JsonStream constructor.
     * @param string $json
     */
    public function __construct($json)
    {
        parent::__construct($json, new JsonProcessor());
    }
}
