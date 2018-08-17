<?php

declare(strict_types=1);

namespace Peak\Blueprint;

/**
 * Interface ResolverInterface
 * @package Peak\Bedrock\Resolver
 */
interface Resolvable
{
    /**
     * Try to return a resolved item or throw an exception
     *
     * @param mixed $item
     * @return mixed
     */
    public function resolve($item);
}
