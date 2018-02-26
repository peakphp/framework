<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use \Exception;

class ArrayProcessor extends AbstractProcessor
{
    /**
     * Array processor
     * @throws Exception
     */
    public function process($data)
    {
        if (!is_array($data)) {
            throw new Exception(__CLASS__.': config ['.$data.'] is not an array');
        }

        $this->content = $data;
    }
}
