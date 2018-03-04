<?php

namespace Peak\Config\Processors;

use Peak\Config\AbstractProcessor;
use Peak\Config\Exceptions\ProcessorException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlProcessor extends AbstractProcessor
{
    /**
     * @param $data
     * @throws ProcessorException, ParseException
     */
    public function process($data)
    {
        if (!class_exists('Symfony\Component\Yaml\Yaml')) {
            throw new ProcessorException(__CLASS__.' require symfony/yaml to work properly');
        }

        $this->content = Yaml::parse($data);
    }
}
