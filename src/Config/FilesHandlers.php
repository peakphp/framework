<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exception\NoFileHandlersException;
use Peak\Config\Loader\LoaderInterface;
use Peak\Config\Processor\ProcessorInterface;

/**
 * Class FilesHandlers
 * @package Peak\Config
 */
class FilesHandlers
{
    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @var array
     */
    protected $defaultHandlers = [
        'php' => [
            'loader' => \Peak\Config\Loader\PhpLoader::class,
            'processor' => \Peak\Config\Processor\ArrayProcessor::class
        ],
        'json' => [
            'loader' => \Peak\Config\Loader\DefaultLoader::class,
            'processor' => \Peak\Config\Processor\JsonProcessor::class
        ],
        'yml' => [
            'loader' => \Peak\Config\Loader\DefaultLoader::class,
            'processor' => \Peak\Config\Processor\YamlProcessor::class
        ],
        'ini' => [
            'loader' => \Peak\Config\Loader\DefaultLoader::class,
            'processor' => \Peak\Config\Processor\IniProcessor::class
        ],
        'txt' => [
            'loader' => \Peak\Config\Loader\TextLoader::class,
            'processor' => \Peak\Config\Processor\ArrayProcessor::class
        ],
        'log' => [
            'loader' => \Peak\Config\Loader\TextLoader::class,
            'processor' => \Peak\Config\Processor\ArrayProcessor::class
        ],
        'xml' => [
            'loader' => \Peak\Config\Loader\DefaultLoader::class,
            'processor' => \Peak\Config\Processor\XmlProcessor::class
        ],
        'env' => [
            'loader' => \Peak\Config\Loader\DefaultLoader::class,
            'processor' => \Peak\Config\Processor\EnvProcessor::class
        ],
    ];


    /**
     * FilesHandlers constructor.
     *
     * @param array|null $handlers if null, $defaultHandlers is used instead
     */
    public function __construct(?array $handlers)
    {
        if (null === $handlers) {
            $handlers = $this->defaultHandlers;
        }
        $this->handlers = $handlers;
    }

    /**
     * Check if we have a specific file handlers
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return array_key_exists($name, $this->handlers);
    }

    /**
     * Get a file handlers
     *
     * @param string $name
     * @return array
     * @throws NoFileHandlersException
     */
    public function get(string $name): array
    {
        if (!$this->has($name)) {
            throw new NoFileHandlersException($name);
        }

        return $this->handlers[$name];
    }

    /**
     * Get a file loader
     *
     * @param string $name
     * @return LoaderInterface
     * @throws NoFileHandlersException
     */
    public function getLoader(string $name): LoaderInterface
    {
        $handlers = $this->get($name);
        return new $handlers['loader']();
    }

    /**
     * Get a file processor
     *
     * @param string $name
     * @return ProcessorInterface
     * @throws NoFileHandlersException
     */
    public function getProcessor(string $name): ProcessorInterface
    {
        $handlers = $this->get($name);
        return new $handlers['processor']();
    }

    /**
     * Get current files handlers
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->handlers;
    }

    /**
     * Add or override a file handlers
     *
     * @param string $name
     * @param string $loader
     * @param string $processor
     * @return FilesHandlers
     */
    public function set(string $name, string $loader, string $processor): FilesHandlers
    {
        $this->handlers[$name] = [
            'loader' => new $loader(),
            'processor' => new $processor(),
        ];
        return $this;
    }
}
