<?php

namespace Peak\Config\Processors;

use Peak\Common\Collection;
use Peak\Config\AbstractProcessor;

class CollectionProcessor extends AbstractProcessor
{

    /**
     * @param Collection $data
     * @throws \Exception
     */
    public function process($data)
    {
        if (!$data instanceof Collection) {
            throw new \Exception(__CLASS__.' expect data to be an instance of Collection');
        }

        $this->content = $data->toArray();
    }
}
