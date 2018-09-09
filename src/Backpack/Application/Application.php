<?php

namespace Peak\Backpack\Application;

use Peak\Bedrock\Http\StackFactory;
use Peak\Di\Container;

/**
 * Class Application
 * @package Peak\Backpack\Application
 */
class Application extends \Peak\Bedrock\Application\Application
{
    /**
     * @param $object
     * @param null $alias
     * @return $this
     * @throws \Exception
     */
    public function setInContainer($object, $alias = null)
    {
        $this->checkIfPeakDi();
        $this->getContainer()->set($object, $alias);
        return $this;
    }

    /**
     * @param string $class
     * @param array $args
     * @param array|null $explicit
     * @return mixed
     * @throws \Exception
     */
    public function createWithContainer(string $class, array $args = [], array $explicit = null)
    {
        $this->checkIfPeakDi();
        return $this->getContainer()->create($class, $args, $explicit);
    }

    /**
     * @param $handlers
     * @return \Peak\Bedrock\Http\Stack
     */
    public function createStack($handlers)
    {
        return (new StackFactory($this->getHandlerResolver()))
            ->create($handlers);
    }

    /**
     * @throws \Exception
     */
    private function checkIfPeakDi()
    {
        if (!$this->getContainer() instanceof Container) {
            throw new \Exception('setInContainer() only work with Peak\Di\Container');
        }
    }
}