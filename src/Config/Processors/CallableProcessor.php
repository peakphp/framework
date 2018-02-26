<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use \Exception;

class CallableProcessor extends AbstractProcessor
{
    /**
     * Process
     * @throws Exception
     */
    public function process($data)
    {
        $fn = $data;
        $this->content = $fn();

        if (!is_array($this->content)) {
            throw new Exception(__CLASS__.': Closure/Callable config must return an array');
        }
    }
}
