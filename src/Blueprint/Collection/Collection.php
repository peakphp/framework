<?php

declare(strict_types=1);

namespace Peak\Blueprint\Collection;

use Peak\Blueprint\Common\Arrayable;
use \ArrayAccess;
use \Countable;
use \IteratorAggregate;
use \Serializable;

interface Collection extends ObjectAccess, ArrayAccess, Arrayable, Countable, IteratorAggregate, Serializable
{
}
