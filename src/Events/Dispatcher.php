<?php

namespace Peak\Events;

use closure;

class Dispatcher 
{
    protected $events = [];

    /**
     * Attach an event callback. An event name can have multiple callback
     * 
     * @param  string  $name     
     * @param  closure $callback 
     * @return $this           
     */
    public function attach($name, $callback) 
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = array();
        }
        $this->events[$name][] = $callback;

        return $this;
    }

    /**
     * Has an event
     * 
     * @param  string  $name
     * @return boolean      
     */
    public function hasEvent($name)
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
        if(is_string($ev)) $events[] = $events;
        elseif(is_array($ev)) $events = $ev;

        foreach($events as $event) {
            unset($this->events[$event]);
        }

        return $this;
    }

    /**
     * Detach all events
     */
    public function detachAll()
    {
        $this->events = [];
        return $this;
    }

    /**
     * Trigger one or many events
     * 
     * @param  string|array $ev
     * @param  mixed $argv 
     * @param  array $data
     */
    public function fire($ev, $argv = null) 
    {
        if(empty($this->events)) return;
        $events = [];

        if(is_string($ev)) $events[] = $ev;
        elseif(is_array($ev)) $events = $ev;

        foreach($events as $event) {
            if(array_key_exists($event, $this->events)) {
                foreach ($this->events[$event] as $i => $callback) {
                    $this->handleCallback($event, $callback, $argv);
                }
            }
        }
    }

    /**
     * Handle an event callback
     * 
     * @param  string $event    
     * @param  mixed  $callback 
     * @param  mixed  $argv               
     */
    protected function handleCallback($event, $callback, $argv = null)
    {
        if(is_callable($callback)) {
            $callback($argv);
        }
        else if(is_string($callback) && class_exists($callback)) {
            $e = new $callback();
            if($e instanceof EventInterface) $e->fire($argv);
            else $this->eventCallbackFail($event, $i);
        }
        else if(is_object($callback) && $callback instanceof EventInterface) {
            $callback->fire($argv);
        }
        else {
            $this->eventCallbackFail($event, $i);
        }
    }

    /**
     * Fail to call an event callback
     * 
     * @param  string  $name  
     * @param  integer $index      
     */
    protected function eventCallbackFail($name, $index)
    {
        throw new \Exception('Event "'.$name.'" #'.$index.' is invalid. Only Closure, Classname or Object instance implementing EventInterface are allowed.');
    }
}
