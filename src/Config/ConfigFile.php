<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\FileNotFoundException;
use Peak\Config\Exceptions\InvalidTypeDefinitionException;
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
     * @var array
     */
    protected static $types = [];

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
            $this->initTypes();
            $type = (new ConfigFileType($source, self::$types))->get();
            $loader = new $type['loader']();
            $processor = new $type['processor']();
        }

        if (!isset($automatic_mode)) {
            if (!isset($loader)) {
                $loader = new $this->default_loader();
            }
            if (!isset($processor)) {
                $processor = new $this->default_processor();
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

    /**
     * Initiate default files types
     */
    protected function initTypes(): void
    {
        if (empty(self::$types)) {
            self::$types = DefaultFileTypes::get();
        }
    }

    /**
     * Add or override a file type definition
     *
     * @param string $name
     * @param string $loader
     * @param string $processor
     */
    public static function setType(string $name, string $loader, string $processor)
    {
        self::$types[$name] = [
            'loader' => $loader,
            'processor' => $processor,
        ];
    }

    /**
     * Overwrite file types definitions
     *
     * @param array $types
     * @throws InvalidTypeDefinitionException
     */
    public static function setTypes(array $types): void
    {
        foreach ($types as $type => $definition) {
            if (!isset($definition['loader']) || !isset($definition['processor'])) {
                throw new InvalidTypeDefinitionException($type);
            }
            self::setType($type, $definition['loader'], $definition['processor']);
        }
    }
}
