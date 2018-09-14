<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

use Peak\Blueprint\Common\Arrayable;
use \ArrayAccess;
use \Countable;
use \IteratorAggregate;
use \Serializable;

/**
 * Interface Dictionary
 * @package Peak\Blueprint\Collection
 */
interface Dictionary extends Collection
{
    /**
     * @param string $item
     * @param null $default
     * @return mixed
     */
    public function get(string $item, $default = null);

    /**
     * @param string $item
     * @param $value
     * @return mixed
     */
    public function set(string $item, $value);

    /**
     * @param string $item
     * @return bool
     */
    public function has(string $item): bool;
}
