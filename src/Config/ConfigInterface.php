<?php

namespace Peak\Config;

/**
 * Interface ConfigInterface
 * @package Peak\Config
 */
interface ConfigInterface
{
    public function get(string $path, $default = null);
    public function set(string $path, $value);
    public function toArray();
}