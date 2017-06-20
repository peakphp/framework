<?php

namespace Peak\Config;

use Peak\Common\ClassFinder;
use Peak\Common\Collection;
use Peak\Common\DotNotationCollection;
use \Exception;

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
     * Configs loader namespace(s)
     * @var array
     */
    protected $namespaces = [
        'Peak\Config\Type'
    ];

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
     * Change type load namespaces
     *
     * @param array $namespaces
     * @return $this
     */
    public function ns(array $namespaces)
    {
        $this->namespaces = $namespaces;
        return $this;
    }

    /**
     * Get config(s) has one merged collection
     *
     * @return Collection
     */
    public function asCollection()
    {
        return $this->load($this->configs, $this->path);
    }

    /**
     * Get config(s) has one merged DotNotation collection
     *
     * @return DotNotationCollection
     */
    public function asDotNotationCollection()
    {
        $collection = $this->load($this->configs, $this->path);
        return new DotNotationCollection($collection->toArray());
    }

    /**
     * Get config(s) has one merged array
     *
     * @return array
     */
    public function asArray()
    {
        return $this->load($this->configs, $this->path)->toArray();
    }

    /**
     * Get config(s) has one merged stdClass
     *
     * @return \stdClass
     */
    public function asObject()
    {
        return $this->load($this->configs, $this->path)->toObject();
    }

    /**
     * Internal config loader loader
     *
     * @param array $files
     * @param null $path
     * @return Collection
     * @throws Exception
     */
    protected function load(array $configs, $path = null)
    {
        $collection = new Collection();

        foreach ($configs as $config) {
            if (isset($path)) {
                $config = $path.'/'.$config;
            }

            $loader = $this->detectType($config);
            $content = $this->getContent($loader);

            $collection->mergeRecursiveDistinct($content);
        }

        return $collection;
    }

    /**
     * Detect config type
     *
     * @param $config
     * @return mixed
     */
    protected function detectType($config)
    {
        $type = null;

        // detect type
        if (is_array($config)) {
            $type = 'ArrayLoader';
        } elseif(is_string($config)) {
            if (file_exists($config)) {
                $ext = pathinfo($config, PATHINFO_EXTENSION);
                $type = ucfirst($ext) . 'Loader';
            } elseif (json_decode($config) && (json_last_error() === JSON_ERROR_NONE)) {
                $type = 'JsonLoader';
            }
        } elseif ($config instanceof Collection) {
            $type = 'CollectionLoader';
        }

        if (is_null($type)) {
            throw new Exception(__CLASS__.': unknown config type for ['.$config.']');
        }

        return $this->initLoaderType($config, ucfirst($type));
    }

    /**
     * Get loader type
     *
     * @param LoaderInterface $loader
     * @return array
     */
    protected function initLoaderType($config, $type)
    {
        $loader = (new ClassFinder($this->namespaces))->findLast($type);

        if (is_null($loader)) {
            if (is_array($config) || is_object($config)) {
                ob_start();
                var_dump($config);
                $config = ob_get_clean();
            }
            throw new Exception(__CLASS__.': no config loader found type ['.$type.'] of ['.$config.']');
        }

        return (new $loader($config));
    }

    /**
     * Get loader content
     *
     * @param LoaderInterface $loader
     * @return mixed
     */
    protected function getContent(LoaderInterface $loader)
    {
        return $loader->getContent();
    }
}
