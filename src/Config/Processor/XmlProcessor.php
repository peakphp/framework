<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Config\Exception\ProcessorException;

use SimpleXMLElement;

class XmlProcessor implements ProcessorInterface
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
