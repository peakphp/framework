<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

use IteratorAggregate;
use Peak\Blueprint\Common\Arrayable;

interface Structure extends IteratorAggregate, Arrayable
{
    public function getStructure(): array;
}
