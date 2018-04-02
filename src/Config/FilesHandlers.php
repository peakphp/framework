<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\InvalidFileHandlerException;
use Peak\Config\Exceptions\NoFileHandlersException;

class FilesHandlers
{
    /**
     * Files handlers
     * @var array
     */
    protected static $handlers = [];

    /**
     * Check if we have a specific file handlers
     *
     * @param string $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return array_key_exists($name, self::$handlers);
    }

    /**
     * Check if handlers array is empty
     *
     * @return bool
     */
    public static function isEmpty(): bool
    {
        return empty(self::$handlers);
    }

    /**
     * Get a file handlers
     *
     * @param string $name
     * @return array
     * @throws NoFileHandlersException
     */
    public static function get(string $name): array
    {
        if (!self::has($name)) {
            throw new NoFileHandlersException($name);
        }

        return self::$handlers[$name];
    }

    /**
     * Get current files handlers
     *
     * @return array
     */
    public static function getAll(): array
    {
        return self::$handlers;
    }

    /**
     * Add or override a file handlers
     *
     * @param string $name file extension
     * @param string $loader file loader
     * @param string $processor file processor
     */
    public static function set(string $name, string $loader, string $processor): void
    {
        self::$handlers[$name] = [
            'loader' => $loader,
            'processor' => $processor,
        ];
    }

    /**
     * Overwrite files handlers definitions
     *
     * @param array $handlers
     * @throws InvalidFileHandlerException
     */
    public static function override(array $handlers): void
    {
        foreach ($handlers as $name => $definition) {
            if (!isset($definition['loader']) || !isset($definition['processor'])) {
                throw new InvalidFileHandlerException($name);
            }
            self::set($name, $definition['loader'], $definition['processor']);
        }
    }
}
