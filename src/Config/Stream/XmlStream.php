<?php

declare(strict_types=1);

namespace Peak\Config\Stream;

use Peak\Config\Processor\XmlProcessor;

/**
 * Class XmlStream
 * @package Peak\Config\Stream
 */
class XmlStream extends DataStream
{
    /**
     * XmlStream constructor.
     * @param $xml
     */
    public function __construct($xml)
    {
        parent::__construct($xml, new XmlProcessor());
    }
}
