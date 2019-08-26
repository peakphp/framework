<?php

declare(strict_types=1);

namespace Peak\Bedrock\Http;

class GroupManager
{
    /**
     * @var int
     */
    private $startedGroups = 0;

    /**
     * @var string
     */
    private $currentPathPrefix = '';

    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @param string $path
     */
    public function startGroup(string $path): void
    {
        $this->startedGroups++;
        $this->currentPathPrefix .= $path;
        $this->handlers[$this->currentPathPrefix] = [];
    }

    /**
     * @param string $path
     */
    public function stopGroup(string $path): void
    {
        $this->startedGroups--;
        $this->currentPathPrefix = substr($this->currentPathPrefix, 0, strlen($this->currentPathPrefix) - strlen($path));
    }

    /**
     * @param $handler
     */
    public function holdHandler($handler): void
    {
        $this->handlers[$this->currentPathPrefix][] = $handler;
    }

    /**
     * @param array $handlers
     */
    public function holdHandlers(array $handlers): void
    {
        foreach ($handlers as $handler) {
            $this->holdHandler($handler);
        }
    }

    /**
     * @param string $path
     * @return array
     */
    public function getHandlers(string $path): array
    {
        return $this->handlers[$path];
    }

    /**
     * @return array
     */
    public function getAllHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param string|null $path
     */
    public function releaseHandlers(string $path = null): void
    {
        (isset($path)) ? $this->handlers[$path] = [] : $this->handlers = [];
    }

    /**
     * @return int
     */
    public function countStartedGroups(): int
    {
        return $this->startedGroups;
    }

    public function currentlyInAGroup(): bool
    {
        return ($this->startedGroups > 0);
    }

    /**
     * @return string
     */
    public function getCurrentPathPrefix(): string
    {
        return $this->currentPathPrefix;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getFullPathFor(string $path): string
    {
        return $this->currentPathPrefix.$path;
    }
}
