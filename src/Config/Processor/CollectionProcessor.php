<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Common\Collection\Collection;
use Peak\Config\Exception\ProcessorException;

/**
 * Class CollectionProcessor
 * @package Peak\Config\Processor
 */
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
