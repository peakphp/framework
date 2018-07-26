<?php

declare(strict_types=1);

namespace Peak\Events;

use Peak\Events\Exception\InvalidCallbackException;

/**
 * Class Dispatcher
 * @package Peak\Events
 */
class Dispatcher
{
    /**
     * Events
     * @var array
     */
    protected $events = [];

    /**
     * Attach an event callback. An event name can have multiple callback
     *
     * @param  string $name
     * @param  mixed  $callback
     * @return $this
     */
    public function attach(string $name, $callback)
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = [];
        }
        $this->events[$name][] = $callback;

        return $this;
    }

    /**
     * Has an event
     *
     * @param  string $name
     * @return boolean
     */
    public function hasEvent(string $name): bool
    {
        return array_key_exists($name, $this->events);
    }

    /**
     * Detach an event name and all is callbacks.
     *
     * @param  string|array $ev event(s) name(s)
     * @return $this
     */
    public function detach($ev)
    {
        $events = [];
        if (is_string($ev)) {
            $events[] = $ev;
        } elseif (is_array($ev)) {
            $events = $ev;
        }

        foreach ($events as $event) {
            unset($this->events[$event]);
        }

        return $this;
    }

    /**
     * Detach all events
     *
     * @return  $this
     */
    public function detachAll()
    {
        $this->events = [];
        return $this;
    }

    /**
     * Trigger one or many events
     *
     * @param string|array $event
     * @param mixed $argv
     * @throws InvalidCallbackException
     */
    public function fire($event, $argv = null)
    {
        if (empty($this->events)) {
            return;
        }
        $events = [];

        if (is_string($event)) {
            $events[] = $event;
        } elseif (is_array($event)) {
            $events = $event;
        }

        foreach ($events as $event) {
            if (array_key_exists($event, $this->events)) {
                foreach ($this->events[$event] as $i => $callback) {
                    $this->handleCallback($event, $i, $callback, $argv);
                }
            }
        }
    }

    /**
     * Handle an event callback
     *
     * @param string $event
     * @param integer $index
     * @param mixed $callback
     * @param mixed $argv
     * @throws InvalidCallbackException
     */
    protected function handleCallback(string $event, int $index, $callback, $argv = null)
    {
        if (is_callable($callback)) {
            $callback($argv);
        } elseif (is_string($callback) && class_exists($callback)) {
            $e = new $callback();
            if ($e instanceof EventInterface) {
                $e->fire($argv);
            } else {
                $this->eventCallbackFail($event, $index);
            }
        } elseif (is_object($callback) && $callback instanceof EventInterface) {
            $callback->fire($argv);
        } else {
            $this->eventCallbackFail($event, $index);
        }
    }

    /**
     * Fail to call an event callback
     *
     * @param string $name
     * @param int $index
     * @throws InvalidCallbackException
     */
    protected function eventCallbackFail(string $name, int $index)
    {
        throw new InvalidCallbackException($name, $index);
    }
}
