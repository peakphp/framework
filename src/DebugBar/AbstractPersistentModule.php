<?php

namespace Peak\DebugBar;

use Peak\Collection\Collection;

abstract class AbstractPersistentModule extends AbstractModule
{
    /**
     * @var array
     */
    protected $defaultStorageData = [];

    /**
     * @var AbstractStorage
     */
    protected $storage;

    /**
     * AbstractPersistentModule constructor.
     * @param AbstractStorage|null $storage
     * @throws \ReflectionException
     */
    public function __construct(?AbstractStorage $storage = null)
    {
        $this->modulePath = $this->getModulePath();
        $this->data = new Collection();
        if ($storage === null) {
            $storage = new SessionStorage();
        }
        $this->storage = $storage;
        $this->initializeDefaultDataStorage();
        $this->initialize();
    }


    /**
     * Initiate module data storage
     */
    protected function initializeDefaultDataStorage()
    {
        if (!isset($this->storage[$this->getName()]) && !empty($this->defaultStorageData)) {
            $this->saveToStorage($this->defaultStorageData);
        }
    }

    /**
     * Get module storage
     * @return mixed
     * @throws \ReflectionException
     */
    protected function getStorage()
    {
        return $this->storage[$this->getName()];
    }

    /**
     * Save data to module storage
     * @param mixed $data
     * @throws \ReflectionException
     */
    protected function saveToStorage($data)
    {
        $this->storage->mergeWith([
            $this->getName() => $data
        ])->save();
    }
}