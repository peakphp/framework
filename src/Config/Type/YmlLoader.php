<?php

namespace Peak\Config\Type;

use Peak\Config\Loader;
use Symfony\Component\Yaml\Yaml;
use \Exception;

class YmlLoader extends Loader
{
    /**
     * Constructor
     *
     * @param $config
     */
    public function __construct($config)
    {
        $this->config = $config;

        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new Exception(__CLASS__.' require symfony/yaml to work properly');
        }

        if (file_exists($config)) {
            $this->content = file_get_contents($config);
        }

        $this->content = Yaml::parse($this->content);
    }
}
