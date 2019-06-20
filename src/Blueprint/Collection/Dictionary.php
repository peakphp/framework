<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

interface Dictionary extends Collection
{
    /**
     * @param string $item
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $item, $default = null);

    /**
     * @param string $item
     * @param mixed $value
     * @return mixed
     */
    public function set(string $item, $value);

    /**
     * @param string $item
     * @return bool
     */
    public function has(string $item): bool;
}
