<?php

namespace Peak\Config\Type;

use Peak\Config\Loader;
use \Exception;

class JsonLoader extends Loader
{
    /**
     * Constructor
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        if (file_exists($config)) {
            $config = file_get_contents($config);
        }
        $this->content = json_decode($config, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception(__CLASS__.': error while decoding file ['.$file.'] > '.json_last_error_msg());
        }
    }
}
