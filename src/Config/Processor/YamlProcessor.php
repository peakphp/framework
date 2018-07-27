<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Config\Exception\ProcessorException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlProcessor
 * @package Peak\Config\Processor
 */
class YamlProcessor implements ProcessorInterface
{
    /**
     * @param $data
     * @return array
     * @throws ProcessorException, ParseException
     */
    public function process($data): array
    {
        if (!class_exists(Yaml::class)) {
            throw new ProcessorException(__CLASS__.' require symfony/yaml to work properly');
        }

        return Yaml::parse($data);
    }
}
