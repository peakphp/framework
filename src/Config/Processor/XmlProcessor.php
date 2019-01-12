<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use SimpleXMLElement;

/**
 * Class XmlProcessor
 * @package Peak\Config\Processor
 */
class XmlProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     */
    public function process($data): array
    {
        $xml = simplexml_load_string($data, SimpleXMLElement::class, LIBXML_NOCDATA);
        // @todo handle possibility of json_* returning false
        return json_decode(json_encode($xml),true);
    }
}
