<?php

declare(strict_types=1);

namespace Peak\Common\Chrono;

use Peak\Common\Traits\MicroTime;

class Chrono
{
    use MicroTime;

    /**
     * @var string
     */
    private $name;

    /**
     * @var float
     */
    private $start;

    /**
     * @var float|null
     */
    private $end = null;

    /**
     * @var array<Step>
     */
    private $steps = [];

    /**
     * Chrono constructor.
     * @param string|null $name
     * @param bool $autostart
     */
    public function __construct(string $name = '')
    {
        $this->name = $name;
        $this->start = $this->getMicroTime();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getSteps(): array
    {
        return $this->steps;
    }

    /**
     * @return bool
     */
    public function stop(): bool
    {
        if (isset($this->end)) {
            return false;
        }
        $this->end = $this->getMicroTime();
        return true;
    }

    /**
     * @param string $id
     * @param string $description
     */
    public function markStep(string $id, string $description = '')
    {
        $time = $this->start;
        if (!empty($this->steps)) {
            $lastStep = $this->steps[count($this->steps) - 1];
            $time = $lastStep->getStart() + $lastStep->getDuration();
        }
        $this->steps[] = new Step($time, $id, $description);
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return (isset($this->end));
    }

    /**
     * @param int $decimalPrecision
     * @return float
     */
    public function getSec(int $decimalPrecision = 2): float
    {
        return $this->formatSec($this->getElapsed(), $decimalPrecision);
    }

    /**
     * @param int $decimalPrecision
     * @return float
     */
    public function getMs(int $decimalPrecision = 2): float
    {
        return $this->formatMs($this->getElapsed(), $decimalPrecision);
    }

    /**
     * @return int
     */
    private function getElapsed(): float
    {
        $end = $this->end ?? $this->getMicroTime();
        return $end - $this->start;
    }
}
