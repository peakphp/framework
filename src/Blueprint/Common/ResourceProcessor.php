<?php

declare(strict_types=1);

namespace Peak\Blueprint\Common;

/**
 * Interface ResourceProcessor
 * @package Peak\Blueprint\Common
 */
interface ResourceProcessor
{
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function process($resource);
}
