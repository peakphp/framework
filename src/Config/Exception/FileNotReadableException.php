<?php

declare(strict_types=1);

namespace Peak\Config\Exception;

class FileNotReadableException extends \Exception
{
    /**
     * @var string
     */
    private $configFile;

    /**
     * FileNotReadableException constructor.
     * @param string $configFile
     */
    public function __construct(string $configFile)
    {
        parent::__construct('Config file '.$configFile.' is not readable');
        $this->configFile = $configFile;
    }

    /**
     * @return string
     */
    public function getConfigFile(): string
    {
        return $this->configFile;
    }

}
