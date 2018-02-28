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

class DefaultFileHandlers
{
    /**
     * Configuration file handlers
     * @var array
     */
    protected static $handlers = [
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
     * Get default file handlers definition
     *
     * @return array
     */
    public static function get(): array
    {
        return self::$handlers;
    }
}
