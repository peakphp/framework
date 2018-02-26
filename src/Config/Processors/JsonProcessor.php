<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use \Exception;

class JsonProcessor extends AbstractProcessor
{

    /**
     * @param $data
     * @throws Exception
     */
    public function process($data)
    {
        // remove comments // and /* */
        $data = preg_replace("#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|([\s\t](//).*)#", '', $data);

        // decode json
        $this->content = json_decode($data, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(__CLASS__.': error while decoding json > '.json_last_error_msg());
        }
    }
}
