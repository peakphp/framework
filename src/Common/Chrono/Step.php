<?php

declare(strict_types=1);

namespace Peak\Common\Chrono;

use Peak\Common\Traits\MicroTime;

class Step
{

    use MicroTime;

    /**
     * @var float
     */
    private $start;

    /**
     * @var float
     */
    private $duration;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * Step constructor.
     * @param float $start
     * @param string $id
     * @param string $description
     */
    public function __construct(float $start, string $id, string $description = '')
    {
        $this->start = $start;
        $this->duration = $this->getMicroTime() - $start;
        $this->id = $id;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return float
     */
    public function getStart(): float
    {
        return $this->start;
    }

    /**
     * @return float
     */
    public function getDuration(): float
    {
        return $this->duration;
    }

    /**
     * @param int $decimalPrecision
     * @return float
     */
    public function getSec(int $decimalPrecision = 2): float
    {
        return $this->formatSec($this->duration, $decimalPrecision);
    }

    /**
     * @param int $decimalPrecision
     * @return float
     */
    public function getMs(int $decimalPrecision = 2): float
    {
        return $this->formatMs($this->duration, $decimalPrecision);
    }
}
