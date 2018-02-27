<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Config\Exceptions\FileTypesNotSupportedException;

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
    protected $types = [];

    /**
     * ConfigFileType constructor.
     *
     * @param string $file
     * @throws FileTypesNotSupportedException
     */
    public function __construct(string $file, array $types = [])
    {
        $this->extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $this->types = $types;

        if (!array_key_exists($this->extension, $this->types)) {
            throw new FileTypesNotSupportedException($this->extension);
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
