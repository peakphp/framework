<?php

namespace Peak\Config;

/**
 * Same as ConfigLoader but don't fail if config(s)
 * file(s) does not exists or can't be process
 *
 * @package Peak\Config
 */
class ConfigSoftLoader extends ConfigLoader
{
    /**
     * Constructor
     *
     * @param array $configs
     * @param string|null $path   path prefix string for $files
     */
    public function __construct(array $configs, $path = null)
    {
        parent::__construct($configs, $path);
        $this->soft = true;
    }
}
