<?php

declare(strict_types=1);

namespace Peak\Blueprint\Common;

interface ResourceResolver
{
    /**
     * This method take a raw resource of any kind and try to return a more meaningful exploitable object based on it.
     * An exception should be raised when detecting an invalid resource.
     *
     * @param mixed $resource
     * @return mixed
     */
    public function resolve($resource);
}
