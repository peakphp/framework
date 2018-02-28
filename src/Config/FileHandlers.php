<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\NoFileHandlersException;
use Peak\Config\Exceptions\InvalidFileHandlerException;

class FileHandlers
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * File type handlers
     * @var array
     */
    protected static $handlers = [];

    /**
     * ConfigFileType constructor.
     *
     * @param string $file
     * @throws NoFileHandlersException
     */
    public function __construct(string $file)
    {
        $this->extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        if (empty(self::$handlers)) {
            self::$handlers = DefaultFileHandlers::get();
        }

        if (!array_key_exists($this->extension, self::$handlers)) {
            throw new NoFileHandlersException($this->extension);
        }
    }

    /**
     * Get Loader and Processor class names
     *
     * @return array
     */
    public function get(): array
    {
        return self::$handlers[$this->extension];
    }

    /**
     * Get loader class name
     *
     * @return string
     */
    public function getLoader(): string
    {
        return self::$handlers[$this->extension]['loader'];
    }

    /**
     * Get processor class name
     *
     * @return string
     */
    public function getProcessor(): string
    {
        return self::$handlers[$this->extension]['processor'];
    }

    /**
     * Add or override a file handler
     *
     * @param string $name file extension
     * @param string $loader file loader
     * @param string $processor file processor
     */
    public static function setHandler(string $name, string $loader, string $processor): void
    {
        self::$handlers[$name] = [
            'loader' => $loader,
            'processor' => $processor,
        ];
    }

    /**
     * Overwrite file types definitions
     *
     * @param array $handlers
     * @throws InvalidFileHandlerException
     */
    public static function setHandlers(array $handlers): void
    {
        foreach ($handlers as $name => $definition) {
            if (!isset($definition['loader']) || !isset($definition['processor'])) {
                throw new InvalidFileHandlerException($name);
            }
            self::setHandler($name, $definition['loader'], $definition['processor']);
        }
    }
}
