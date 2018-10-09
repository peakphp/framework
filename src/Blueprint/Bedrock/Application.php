<?php

declare(strict_types=1);

namespace Peak\Blueprint\Bedrock;

use Peak\Blueprint\Collection\Dictionary;
use Peak\Blueprint\Http\ResponseEmitter;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Interface Application
 * @package Peak\Blueprint\Bedrock
 */
interface Application extends RequestHandlerInterface
{
    /**
     * @return Kernel
     */
    public function getKernel(): Kernel;

    /**
     * @param string $property
     * @param null $default
     * @return mixed
     */
    public function getProp(string $property, $default = null);

    /**
     * @param string $property
     * @return bool
     */
    public function hasProp(string $property): bool;

    /**
     * Get application "properties" object
     * @return null|Dictionary
     */
    public function getProps(): ?Dictionary;

    /**
     * Handle the request and emit a response
     *
     * @param ServerRequestInterface $request
     * @param ResponseEmitter $emitter
     * @return mixed
     */
    public function run(ServerRequestInterface $request, ResponseEmitter $emitter);
}
