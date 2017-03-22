<?php

namespace Peak\Di;

use \Exception;
use \ReflectionClass;
use \ReflectionException;

/**
 * Dependency Class Inspector
 */
class ClassInspector
{
    /**
     * Get class dependencies method.
     * By default, method is the constructor itself
     *
     * @param  string $class
     * @param  string $method
     * @return object
     */
    public function inspect($class, $method = '__construct')
    {
        $dependencies = [];

        try {
            $r = new ReflectionClass($class);

            if ($r->hasMethod($method)) {
                $rp = $r->getMethod($method)->getParameters();

                foreach ($rp as $p) {
                    $prop = $p->name;

                    $dependencies[$prop] = [];
                    $dependencies[$prop]['optional'] = $p->isOptional();

                    try {
                        $class = $p->getClass();

                        if (isset($class)) {
                            $dependencies[$prop]['class'] = $class->name;
                        } else {
                        }
                    } catch (ReflectionException $e) {
                        $dependencies[$prop]['error'] = $e->getMessage();
                    }
                }
            }
        } catch (ReflectionException $e) {
            throw new Exception('Can\'t resolve classname '.$class.'::'.$method);
        }

        return $dependencies;
    }
}
