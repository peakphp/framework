<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Collection\Collection;
use Peak\Config\Exception\ProcessorTypeException;

class CollectionProcessor implements ResourceProcessor
{

    /**
     * @param Collection $data
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!$data instanceof Collection) {
            throw new ProcessorTypeException(__CLASS__, Collection::class, $data);
            //throw new ProcessorException(__CLASS__.' expects data to be an instance of Collection. '.gettype($data).' given.');
        }

        return $data->toArray();
    }
}
