<?php

declare(strict_types=1);

namespace Peak\Blueprint\Config;

use Peak\Blueprint\Common\Arrayable;
use \Serializable;

/**
 * Interface ConfigInterface
 * @package Peak\Config
 */
interface Config extends Arrayable, Serializable
{
    /**
     * @param string $path
     * @param null $default
     * @return mixed
     */
    public function get(string $path, $default = null);

    /**
     * @param string $path
     * @param $value
     * @return mixed
     */
    public function set(string $path, $value);

    /**
     * @param string $path
     * @return bool
     */
    public function has(string $path): bool;
}
