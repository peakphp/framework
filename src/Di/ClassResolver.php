<?php

namespace Peak\Di;

use Peak\Exception;
use Peak\Di\Container;
use Peak\Di\ClassInspector;
use Peak\Di\InterfaceResolver;
use Peak\Di\ExplicitResolver;

use Closure;

/**
 * Class Dependencies Resolver
 */
class ClassResolver
{
    /**
     * ClassInspector
     * @var Peak\Di\ClassInspector
     */
    protected $inspector;

    /**
     * Interface resolver
     * @var Peak\Di\InterfaceResolver
     */
    protected $iresolver;

    /**
     * Explicit resolver
     * @var Peak\Di\ExplicitResolver
     */
    protected $explicit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->inspector = new ClassInspector();
        $this->explicit  = new ExplicitResolver();
        $this->iresolver = new InterfaceResolver();
    }

    /**
     * Resolve class arguments dependencies
     * 
     * @param  string $class
     * @return object
     */
    public function resolve($class, Container $container, array $args = [], $explicit = [])
    {
        $dependencies = $this->inspector->inspect($class);
        $class_args   = [];
        $class_count  = 0;
        $i            = 0;

        foreach($dependencies as $key => $d) {

            if(isset($d['error'])) {
                throw new \Exception($d['error']);
            }

            // its a class or an interface
            if(isset($d['class'])) {

                $name = $d['class'];
                ++$class_count;

                // look for object in explicit dependency declaration
                $result = $this->explicit->resolve($name, $explicit);
                if($result !== null) {
                    $class_args[] = $result;
                }
                // check if container has a stored instance
                else if($container->hasInstance($name)) {
                    $class_args[] = $container->getInstance($name);
                }
                else {
                    // otherwise check if we are
                    // dealing with an interface dependency
                    if(interface_exists($name)) {
                        $class_args[] = $this->iresolver->resolve($name, $container, $explicit);
                    }
                    // or resolve dependency by trying to instanciate object classname string
                    else {
                        $child_args = [];
                        if(array_key_exists($name, $args)) {
                            $child_args = $args[$name];
                        }
                        $class_args[] = $container->instantiate($name, $child_args, $explicit);
                    }
                }
            }
            // everything else that is not a type of class or interface
            else if(array_key_exists($i - ($class_count), $args)) {
                $class_args[] = $args[$i - $class_count];
            }

            ++$i;
        }

        return $class_args;
    } 
}
