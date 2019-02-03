<?php

declare(strict_types=1);

namespace Peak\Blueprint\View;

/**
 * Interface View
 * @package Peak\Blueprint\Http
 */
interface View
{
    /**
     * @param string $var
     * @return mixed
     * @throws \Exception
     */
    public function &__get(string $var);

    /**
     * @param string $var
     * @return bool
     */
    public function __isset(string $var): bool;

    /**
     * @return string|false
     */
    public function render();

    /**
     * @return array
     */
    public function getVars(): array;
}
