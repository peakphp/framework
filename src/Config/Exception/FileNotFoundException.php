<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class FileNotFoundException extends \Exception
{
    /**
     * @var string
     */
    private $configFile;

    /**
     * FileNotFoundException constructor.
     * @param string $configFile
     */
    public function __construct(string $configFile)
    {
        parent::__construct('Config file '.$configFile.' not found');
        $this->configFile = $configFile;
    }

    /**
     * @return string
     */
    public function getConfigFile(): string
    {
        return $this->file;
    }
}
