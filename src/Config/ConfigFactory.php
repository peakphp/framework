<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Common\Collection\Collection;
use Peak\Config\Exception\UnknownResourceException;
use Peak\Config\Processor\ArrayProcessor;
use Peak\Config\Processor\CallableProcessor;
use Peak\Config\Processor\CollectionProcessor;
use Peak\Config\Processor\StdClassProcessor;
use Peak\Config\Stream\DataStream;
use Peak\Config\Stream\FileStream;
use Peak\Config\Stream\StreamInterface;

class ConfigFactory
{
    /**
     * @var FilesHandlers
     */
    protected $filesHandlers;

    /**
     * @param FilesHandlers $filesHandlers
     * @return $this
     */
    public function setFilesHandlers(FilesHandlers $filesHandlers)
    {
        $this->filesHandlers = $filesHandlers;
        return $this;
    }

    /**
     * @param array $resources
     * @return Config
     * @throws UnknownResourceException
     */
    public function loadResource(array $resources): Config
    {
        $config = new Config();
        foreach ($resources as $resource) {
            $stream = $this->getStreamFrom($resource);
            $config->mergeRecursiveDistinct($stream->get());
        }
        return $config;
    }

    /**
     * Create a stream from a resource
     *
     * @param $resource
     * @return StreamInterface
     * @throws UnknownResourceException
     */
    protected function getStreamFrom($resource): StreamInterface
    {
        // detect best way to load and process configuration content
        if (is_array($resource)) {
            return new DataStream($resource, new ArrayProcessor());
        } elseif (is_callable($resource)) {
            return new DataStream($resource, new CallableProcessor());
        } elseif ($resource instanceof Collection) {
            return new DataStream($resource, new CollectionProcessor());
        } elseif ($resource instanceof StreamInterface) {
            return $resource;
        } elseif ($resource instanceof \stdClass) {
            return new DataStream($resource, new StdClassProcessor());
        } elseif (is_string($resource)) {
            if (!isset($this->filesHandlers)) {
                $this->setFilesHandlers(new FilesHandlers());
            }
            return new FileStream($resource, $this->filesHandlers);
        }

        throw new UnknownResourceException($resource);
    }
}
