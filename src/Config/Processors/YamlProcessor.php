<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use Symfony\Component\Yaml\Yaml;
use \Exception;

class YamlProcessor extends AbstractProcessor
{
    /**
     * @param $data
     * @throws Exception
     */
    public function process($data)
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new Exception(__CLASS__.' require symfony/yaml to work properly');
        }

        $this->content = Yaml::parse($data);
    }
}
