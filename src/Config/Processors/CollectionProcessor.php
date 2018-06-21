<?php

declare(strict_types=1);

namespace Peak\Config\Processors;

use Peak\Common\Collection\Collection;
use Peak\Config\Exceptions\ProcessorException;
use Peak\Config\ProcessorInterface;

class CollectionProcessor implements ProcessorInterface
{

    /**
     * @param Collection $data
     * @throws ProcessorException
     */
    public function process($data): array
    {
        if (!$data instanceof Collection) {
            throw new ProcessorException(__CLASS__.' expects data to be an instance of Collection. '.gettype($data).' given.');
        }

        return $data->toArray();
    }
}
