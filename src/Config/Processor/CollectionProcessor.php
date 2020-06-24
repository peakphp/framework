<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Collection\Collection;
use Peak\Config\Exception\ProcessorTypeException;

class CollectionProcessor implements ResourceProcessor
{
    /**
     * @param mixed $data
     * @return array
     * @throws ProcessorTypeException
     */
    public function process($data): array
    {
        if (!$data instanceof Collection) {
            throw new ProcessorTypeException(__CLASS__, Collection::class, $data);
        }

        return $data->toArray();
    }
}
