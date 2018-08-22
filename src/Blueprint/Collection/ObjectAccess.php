<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

/**
 * Interface ObjectAccess
 * @package Peak\Blueprint\Collection
 */
interface ObjectAccess
{
    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set(string $key, $value);

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool;

    /**
     * @param string $key
     */
    public function __unset(string $key);
}