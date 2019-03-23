<?php

declare(strict_types=1);

namespace Peak\Config;

use Peak\Blueprint\Config\Config;
use Peak\Blueprint\Collection\Collection;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\Config\Stream;
use Peak\Config\Exception\UnknownResourceException;
use Peak\Config\Processor\ArrayProcessor;
use Peak\Config\Processor\CallableProcessor;
use Peak\Config\Processor\CollectionProcessor;
use Peak\Config\Processor\ConfigProcessor;
use Peak\Config\Processor\StdClassProcessor;
use Peak\Config\Stream\ConfigStream;
use Peak\Config\Stream\DataStream;
use Peak\Config\Stream\FileStream;
use \stdClass;

use function is_array;
use function is_callable;
use function is_string;

class ConfigResolver implements ResourceResolver
{
    /**
     * @var null|FilesHandlers
     */
    private $filesHandlers;

    /**
     * ConfigResolver constructor.
     *
     */
    public function __construct(?FilesHandlers $filesHandlers)
    {
        $this->filesHandlers = $filesHandlers;
    }

    /**
     * Resolve a config resource to a valid StreamInterface
     *
     * @param mixed $resource
     * @return Stream
     * @throws UnknownResourceException
     */
    public function resolve($resource): Stream
    {
        // detect best way to load and process configuration content
        if (is_array($resource)) {
            return new DataStream($resource, new ArrayProcessor());
        } elseif (is_callable($resource)) {
            return new DataStream($resource, new CallableProcessor());
        } elseif ($resource instanceof Config) {
            return new DataStream($resource, new ConfigProcessor());
        } elseif ($resource instanceof Collection) {
            return new DataStream($resource, new CollectionProcessor());
        } elseif ($resource instanceof Stream) {
            return $resource;
        } elseif ($resource instanceof stdClass) {
            return new DataStream($resource, new StdClassProcessor());
        } elseif (is_string($resource)) {
            $filesHandlers = $this->filesHandlers;
            if (null === $filesHandlers) {
                $filesHandlers = new FilesHandlers(null);
            }
            return new FileStream($resource, $filesHandlers);
        }

        throw new UnknownResourceException($resource);
    }
}
