<?php

declare(strict_types=1);

namespace Peak\Di;

use Closure;
use function array_key_exists;
use function is_array;
use function is_object;

class ExplicitResolver
{
    /**
     * Resolve class arguments dependencies
     *
     * @param string $needle
     * @param Container $container
     * @param mixed $explicit
     * @return mixed|null
     */
    public function resolve(string $needle, Container $container, $explicit = null)
    {
        // Check for explicit dependency closure or object instance
        if (is_array($explicit) && array_key_exists($needle, $explicit)) {
            if ($explicit[$needle] instanceof Closure) {
                return $explicit[$needle]($container);
            } elseif (is_object($explicit[$needle])) {
                return $explicit[$needle];
            }
        } elseif ($explicit instanceof Closure) {
            return $explicit($container, $needle);
        }
        return null;
    }
}
