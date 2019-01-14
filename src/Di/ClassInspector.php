<?php

declare(strict_types=1);

namespace Peak\Di;

use \Exception;
use \ReflectionClass;
use \ReflectionException;

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
