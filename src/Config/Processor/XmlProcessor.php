<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;

use SimpleXMLElement;

/**
 * Class XmlProcessor
 * @package Peak\Config\Processor
 */
class XmlProcessor implements ResourceProcessor
{
    /**
     * @param $data
     * @return array
     * @throws ProcessorException
     */
    public function process($data): array
    {
        $xml = simplexml_load_string($data, SimpleXMLElement::class, LIBXML_NOCDATA);
        return json_decode(json_encode($xml),true);
    }
}
