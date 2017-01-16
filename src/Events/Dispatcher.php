<?php
namespace Peak\Events;

use closure;

class Dispatcher 
{
    protected $events = [];

    /**
     * Attach an event.
     * 
     * @param  string $name     
     * @param  closure $callback 
     * @return $this           
     */
    public function attach($name, closure $callback) 
    {
        if (!isset($this->events[$name])) {
            $this->events[$name] = array();
        }
        $this->events[$name][] = $callback;

        return $this;
    }

    /**
     * Detach an event. 
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
    public function fire($ev, $argv, ...$data) 
    {
        $events = [];

        if(is_string($ev)) $events[] = $ev;
        elseif(is_array($ev)) $events = $ev;

        foreach($events as $event) {
            if(array_key_exists($event, $this->events)) {
                foreach ($this->events[$event] as $callback) {
                    $callback($argv, $data);
                }
            }
        }
 
    }
}