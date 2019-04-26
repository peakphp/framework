<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

use Peak\Blueprint\Common\Arrayable;
use \IteratorAggregate;

interface Structure extends IteratorAggregate, Arrayable
{
    public function getStructure(): array;
}
