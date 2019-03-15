<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use SimpleXMLElement;

use function simplexml_load_string;
use function json_decode;
use function json_encode;

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
