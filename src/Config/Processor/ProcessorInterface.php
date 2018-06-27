<?php

declare(strict_types=1);

namespace Peak\Config\Processor;

interface ProcessorInterface
{
    public function process($data): array;
}
