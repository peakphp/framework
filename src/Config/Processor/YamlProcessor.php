<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

use Peak\Blueprint\Common\ResourceProcessor;
use Peak\Config\Exception\ProcessorException;
use Symfony\Component\Yaml\Yaml;

use function class_exists;

class YamlProcessor implements ResourceProcessor
{
    /**
     * @param string $data
     * @return array
     * @throws ProcessorException, ParseException
     */
    public function process($data): array
    {
        if (!class_exists(Yaml::class)) {
            throw new ProcessorException(__CLASS__.' require PHP extension symfony/yaml component to work properly');
        }

        $yamlParsedData = Yaml::parse($data);

        if ($yamlParsedData === false || !is_array($yamlParsedData)) {
            throw new ProcessorException(__CLASS__.' fail to parse yaml data');
        }

        return $yamlParsedData;
    }
}
