<?php

declare(strict_types=1);

namespace Peak\Blueprint\Common;

/**
 * Interface ResourceLoader
 * @package Peak\Blueprint\Common
 */
interface ResourceLoader
{
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function load($resource);
}
