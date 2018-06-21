<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\FileNotFoundException;
use Peak\Config\Loaders\DefaultLoader;
use Peak\Config\Processors\ArrayProcessor;

class ConfigFile
{
    /**
     * @var array
     */
    protected $processed_content;

    /**
     * @var string
     */
    protected $default_loader = DefaultLoader::class;

    /**
     * @var string
     */
    protected $default_processor = ArrayProcessor::class;

    /**
     * ConfigFile constructor
     *
     * @param string $source
     * @param LoaderInterface|null $loader
     * @param ProcessorInterface|null $processor
     * @throws Exceptions\InvalidFileHandlerException
     * @throws Exceptions\NoFileHandlersException
     * @throws FileNotFoundException
     */
    public function __construct(string $source, LoaderInterface $loader = null, ProcessorInterface $processor = null)
    {
        if (!file_exists($source)) {
            throw new FileNotFoundException($source);
        }

        if (!isset($loader) && !isset($processor)) {
            // automatic detection of file
            $automatic_mode = true;
            $handlers = $this->resolveWithFilesHandlers($source);
            $loader = new $handlers['loader']();
            $processor = new $handlers['processor']();
        }

        if (!isset($automatic_mode)) {
            if (!isset($loader)) {
                $loader = new $this->default_loader();
            }
            if (!isset($processor)) {
                $processor = new $this->default_processor();
            }
        }

        $this->processed_content = $processor->process($loader->load($source));
    }

    /**
     * Get config file processed content
     *
     * @return array
     */
    public function get(): array
    {
        return $this->processed_content;
    }

    /**
     * Resolve source with FilesHandlers when no loader and processor are not specified
     *
     * @param $source
     * @return array
     * @throws Exceptions\InvalidFileHandlerException
     * @throws Exceptions\NoFileHandlersException
     */
    public function resolveWithFilesHandlers($source)
    {
        $extension = strtolower(pathinfo($source, PATHINFO_EXTENSION));

        if (FilesHandlers::isEmpty()) {
            FilesHandlers::override(DefaultFilesHandlers::get());
        }

        return FilesHandlers::get($extension);
    }
}
