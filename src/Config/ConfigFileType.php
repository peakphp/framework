<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Loaders\DefaultLoader;
use Peak\Config\Loaders\PhpLoader;
use Peak\Config\Loaders\TextLoader;
use Peak\Config\Processors\ArrayProcessor;
use Peak\Config\Processors\IniProcessor;
use Peak\Config\Processors\JsonProcessor;
use Peak\Config\Processors\YamlProcessor;
use Peak\Config\Exceptions\UnknownFileTypeException;

class ConfigFileType
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * Known configuration file type
     * @var array
     */
    protected $types = [
        'php' => [
            'loader' => PhpLoader::class,
            'processor' => ArrayProcessor::class
        ],
        'json' => [
            'loader' => DefaultLoader::class,
            'processor' => JsonProcessor::class
        ],
        'yml' => [
            'loader' => DefaultLoader::class,
            'processor' => YamlProcessor::class
        ],
        'ini' => [
            'loader' => DefaultLoader::class,
            'processor' => IniProcessor::class
        ],
        'txt' => [
            'loader' => TextLoader::class,
            'processor' => ArrayProcessor::class
        ],
        'log' => [
            'loader' => TextLoader::class,
            'processor' => ArrayProcessor::class
        ],
    ];

    /**
     * ConfigFileType constructor.
     *
     * @param string $file
     * @throws UnknownFileTypeException
     */
    public function __construct(string $file)
    {
        $this->extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (!array_key_exists($this->extension, $this->types)) {
            throw new UnknownFileTypeException($this->extension);
        }
    }

    /**
     * Get Loader and Processor class names
     *
     * @return array
     */
    public function get(): array
    {
        return $this->types[$this->extension];
    }

    /**
     * Get loader class name
     *
     * @return string
     */
    public function getLoader(): string
    {
        return $this->types[$this->extension]['loader'];
    }

    /**
     * Get processor class name
     *
     * @return string
     */
    public function getProcessor(): string
    {
        return $this->types[$this->extension]['processor'];
    }
}
