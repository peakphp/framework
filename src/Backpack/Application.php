<?php

namespace Peak\Backpack;

use Peak\Http\StackFactory;
use Peak\Di\Container;

class Application extends \Peak\Bedrock\Application\Application
{
    /**
     * @param mixed $object
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
     * @throws \Exception
     */
    private function checkIfPeakDi()
    {
        if (!$this->getContainer() instanceof Container) {
            throw new \Exception('setInContainer() and createWithContainer() only work with Peak\Di\Container');
        }
    }
}
