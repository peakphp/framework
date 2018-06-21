<?php

declare(strict_types=1);

namespace Peak\Config;

interface LoaderInterface
{
    /**
     * @param mixed $resource
     * @return mixed
     */
    public function load($resource);
}
