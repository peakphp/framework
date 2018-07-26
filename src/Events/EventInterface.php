<?php

declare(strict_types=1);

namespace Peak\Events;

/**
 * Interface EventInterface
 * @package Peak\Events
 */
interface EventInterface
{
    /**
     * @param mixed $argv
     * @return mixed
     */
    public function fire($argv);
}
