<?php

declare(strict_types=1);

namespace Peak\Common\Traits;

trait MicroTime
{
    /**
     * @return float
     */
    protected function getMicroTime(): float
    {
        return microtime(true);
    }

    /**
     * @param float $time
     * @param int $decimalPrecision
     * @return float
     */
    protected function formatMs(float $time, int $decimalPrecision = 2): float
    {
        return round($time * 1000, $decimalPrecision);
    }

    /**
     * @param float $time
     * @param int $decimalPrecision
     * @return float
     */
    protected function formatSec(float $time, int $decimalPrecision = 2): float
    {
        return round($time, $decimalPrecision);
    }
}
