<?php

declare(strict_types=1);

namespace Peak\Config;

interface ProcessorInterface
{
    public function process($data): array;
}
