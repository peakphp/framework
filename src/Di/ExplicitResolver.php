<?php

declare(strict_types=1);

namespace Peak\Di;

use Psr\Container\ContainerInterface;
use \Closure;

/**
 * Class ExplicitResolver
 * @package Peak\Di
 */
class ExplicitResolver
{

    /**
     * Resolve class arguments dependencies
     *
     * @param $needle
     * @param ContainerInterface $container
     * @param null $explicit
     * @return mixed|null
     */
    public function resolve($needle, ContainerInterface $container, $explicit = null)
    {
        // Check for explicit dependency closure or object instance
        if (is_array($explicit) && array_key_exists($needle, $explicit)) {
            if ($explicit[$needle] instanceof Closure) {
                return $explicit[$needle]($container);
            } elseif (is_object($explicit[$needle])) {
                return $explicit[$needle];
            }
        } elseif ($explicit instanceof Closure) {
            return $explicit($container);
        }
        return null;
    }
}
