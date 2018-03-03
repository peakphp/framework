<?php

namespace Peak\Config\Processors;

use Peak\Common\Collection;
use Peak\Config\AbstractProcessor;
use Peak\Config\Exceptions\ProcessorException;

class CollectionProcessor extends AbstractProcessor
{

    /**
     * @param Collection $data
     * @throws ProcessorException
     */
    public function process($data)
    {
        if (!$data instanceof Collection) {
            throw new ProcessorException(__CLASS__.' expects data to be an instance of Collection. '.gettype($data).' given.');
        }

        $this->content = $data->toArray();
    }
}
