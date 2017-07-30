<?php

namespace Peak\Config\Type;

use Peak\Config\Loader;
use \Exception;

class TxtLoader extends Loader
{
    /**
     * Constructor
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        $handle = fopen($config, 'r');

        if (!$handle) {
            throw new Exception(__CLASS__ . ': unable to load ' . $config);
        }

        while (($line = fgets($handle)) !== false) {
            $this->content[] = trim($line);
        }

        fclose($handle);
    }
}
