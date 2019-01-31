<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Exception\ClassNotFoundException;
use Peak\Di\Exception\MethodNotFoundException;
use \Exception;
use \ReflectionClass;

/**
 * Class ClassInspector
 * @package Peak\Di
 */
class ClassInspector
{
    /**
     * Get class dependencies method.
     * By default, method is the constructor itself
     *
     * @param mixed $class
     * @param string $method
     * @return array
     * @throws Exception
     */
    public function inspect($class, $method = '__construct')
    {
        if (is_string($class) && !class_exists($class)) {
            throw new ClassNotFoundException($class);
        }
        $dependencies = [];

        $r = new ReflectionClass($class);

        if (!$r->hasMethod($method)) {
            if ($method === '__construct') {
                return $dependencies;
            }
            throw new MethodNotFoundException($class, $method);
        }

        $rp = $r->getMethod($method)->getParameters();

        foreach ($rp as $p) {
            $prop = $p->name;

            $dependencies[$prop] = [];
            $dependencies[$prop]['optional'] = $p->isOptional();

            try {
                $class = $p->getClass();
                if (isset($class)) {
                    $dependencies[$prop]['class'] = $class->name;
                }
            } catch (\Exception $e) {
                $dependencies[$prop]['error'] = $e->getMessage();
            }
        }

        return $dependencies;
    }
}
