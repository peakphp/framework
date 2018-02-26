<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Common\Collection;
use Peak\Common\DotNotationCollection;
use Peak\Config\Exceptions\UnknownTypeException;
use Peak\Config\Processors\ArrayProcessor;
use Peak\Config\Processors\CallableProcessor;
use Peak\Config\Processors\CollectionProcessor;
use \Closure;

class ConfigLoader
{
    /**
     * Configs resources
     * @var array
     */
    protected $configs;

    /**
     * Configs resources path
     * @var null|string
     */
    protected $path = null;

    /**
     * ConfigLoader constructor
     *
     * @param array $configs
     * @param string|null $path   path prefix string for $files
     */
    public function __construct(array $configs, $path = null)
    {
        $this->configs = $configs;
        $this->path = $path;
    }

    /**
     * Get config(s) has one merged collection
     *
     * @return Collection
     * @throws UnknownTypeException
     */
    public function asCollection(): Collection
    {
        return $this->load($this->configs, $this->path);
    }

    /**
     * Get config(s) has one merged DotNotation collection
     *
     * @return DotNotationCollection
     * @throws UnknownTypeException
     */
    public function asDotNotationCollection(): DotNotationCollection
    {
        $collection = $this->load($this->configs, $this->path);
        return new DotNotationCollection($collection->toArray());
    }

    /**
     * Get config(s) has one merged array
     *
     * @return array
     * @throws UnknownTypeException
     */
    public function asArray(): array
    {
        return $this->load($this->configs, $this->path)->toArray();
    }

    /**
     * Get config(s) has one merged stdClass
     *
     * @return \stdClass
     * @throws UnknownTypeException
     */
    public function asObject(): \stdClass
    {
        return $this->load($this->configs, $this->path)->toObject();
    }

    /**
     * Execute a closure with loaded configs collection
     *
     * @param Closure $closure
     * @return mixed
     * @throws UnknownTypeException
     */
    public function asClosure(Closure $closure)
    {
        return $closure($this->load($this->configs, $this->path));
    }

    /**
     * Load all configuration
     *
     * @param array $configs
     * @param null $path
     * @return Collection
     * @throws UnknownTypeException
     */
    protected function load(array $configs, $path = null): Collection
    {
        $collection = new Collection();

        foreach ($configs as $config) {

            if (isset($path)) {
                $config = $path.'/'.$config;
            }

            $content = $this->getContent($config);

            if (is_null($content)) {
                if ($this->soft === true) {
                    continue;
                }
                throw new UnknownTypeException();
            }

            $collection->mergeRecursiveDistinct($content);
        }

        return $collection;
    }

    /**
     * @param $config
     * @return array|null
     */
    protected function getContent($config): ?array
    {
        $content = null;

        // detect best way to load and process configuration content
        if (is_array($config)) {
            $content = $this->processConfig(new ArrayProcessor(), $config);
        } elseif (is_callable($config)) {
            $content = $this->processConfig(new CallableProcessor(), $config);
        } elseif ($config instanceof Collection) {
            $content = $this->processConfig(new CollectionProcessor(), $config);
        } elseif ($config instanceof ConfigFile || $config instanceof ConfigData) {
            $content = $config->get();
        } elseif (is_string($config)) {
            $content = (new ConfigFile($config))->get();
        }

        return $content;
    }

    /**
     * Process content of configuration
     *
     * @param ProcessorInterface $processor
     * @param $config
     * @return array
     */
    protected function processConfig(ProcessorInterface $processor, $config): array
    {
        $processor->process($config);
        return $processor->getContent();
    }
}
