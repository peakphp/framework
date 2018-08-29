<?php

declare(strict_types=1);

namespace Peak\Blueprint\Config;

/**
 * Interface Stream
 * @package Peak\Blueprint\Config
 */
interface Stream
{
    /**
     * @return array
     */
    public function get(): array;
}
