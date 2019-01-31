<?php

declare(strict_types=1);

namespace Peak\Di;

use Peak\Di\Exception\AmbiguousResolutionException;
use Peak\Di\Exception\InterfaceNotFoundException;
use Peak\Di\Exception\ClassDefinitionNotFoundException;
use Peak\Di\Exception\NotFoundException;
use \Exception;
use \InvalidArgumentException;

class ClassResolver
{
    /**
     * ClassInspector
     * @var \Peak\Di\ClassInspector
     */
    protected $inspector;

    /**
     * Interface resolver
     * @var \Peak\Di\InterfaceResolver
     */
    protected $iresolver;

    /**
     * Explicit resolver
     * @var \Peak\Di\ExplicitResolver
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
     * @param mixed $class
     * @param Container $container
     * @param array $args
     * @param mixed $explicit
     * @return array
     * @throws InvalidArgumentException
     */
    /**
     * @param $class
     * @param Container $container
     * @param array $args
     * @param null $explicit
     * @return array
     * @throws AmbiguousResolutionException
     * @throws InterfaceNotFoundException
     * @throws ClassDefinitionNotFoundException
     * @throws NotFoundException
     * @throws \ReflectionException
     */
    public function resolve($class, Container $container, array $args = [], $explicit = null)
    {
        $method = '__construct';

        if (is_array($class)) {
            if (count($class) != 2) {
                throw new InvalidArgumentException('Expecting a valid callback definition');
            }
            // treat $class as a callback
            $method = $class[1];
            $class  = $class[0];
        }

        $dependencies = $this->inspector->inspect($class, $method);
        $class_args   = [];
        $class_count  = 0;
        $i            = 0;

        foreach ($dependencies as $d) {
            if (isset($d['error'])) {
                throw new Exception($d['error']);
            }

            // its a class or an interface
            if (isset($d['class'])) {
                $name = $d['class'];
                ++$class_count;

                // look for object in explicit dependency declaration
                $result = $this->explicit->resolve($name, $container, $explicit);
                if ($result !== null) {
                    $class_args[] = $result;
                } elseif ($container->has($name)) {
                    // check if container has a stored instance
                    $class_args[] = $container->get($name);
                } elseif (!$d['optional'] && interface_exists($name)) {
                    // otherwise check if we are dealing with an interface dependency
                    $class_args[] = $this->iresolver->resolve($name, $container, $explicit);
                } elseif (!$d['optional']) {
                    // or resolve dependency by trying to instantiate object class name string
                    $child_args = [];
                    if (array_key_exists($name, $args)) {
                        $child_args = $args[$name];
                    }
                    $class_args[] = $container->create($name, $child_args, $explicit);
                }
            } elseif (array_key_exists($i - ($class_count), $args)) {
                // everything else that is not a type of class or interface
                $class_args[] = $args[$i - $class_count];
            }

            ++$i;
        }

        return $class_args;
    }
}
