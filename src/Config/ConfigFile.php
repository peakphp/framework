<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\FileNotFoundException;
use Peak\Config\Loaders\TextLoader;
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
    protected $default_loader = TextLoader::class;

    /**
     * @var string
     */
    protected $default_processor = ArrayProcessor::class;

    /**
     * ConfigFile constructor.
     *
     * @param string $source
     * @param LoaderInterface|null $loader
     * @param ProcessorInterface|null $processor
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
            $type = (new ConfigFileType($source))->get();
            $loader = new $type['loader']();
            $processor = new $type['processor']();
        }

        if (!isset($automatic_mode)) {
            if (!isset($loader)) {
                $default_loader = $this->default_loader;
                $loader = new $default_loader();
            }
            if (!isset($processor)) {
                $default_processor = $this->default_processor;
                $processor = new $default_processor();
            }
        }

        $processor->process($loader->loadFileContent($source));
        $this->processed_content = $processor->getContent();
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
}
