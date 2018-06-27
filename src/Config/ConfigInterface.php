<?php

declare(strict_types=1);

namespace Peak\Config;

interface ConfigInterface
{
    public function get(string $path, $default = null);
    public function set(string $path, $value): void;
    public function add(string $path, array $values): void;
    public function has(string $path): bool;
}
