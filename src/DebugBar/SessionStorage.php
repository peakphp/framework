<?php

namespace Peak\DebugBar;

use Peak\Common\Session;
use Peak\Common\Collection;

class SessionStorage extends Collection
{
    /**
     * @var bool
     */
    protected $has_session = false;

    /**
     * SessionData constructor.
     */
    public function __construct()
    {
        $this->has_session = Session::isStarted();
        $this->load();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function get()
    {
        return $this->items;
    }

    /**
     * Load session pkdebugbar
     */
    protected function load()
    {
        if ($this->has_session && isset($_SESSION['pkdebugbar'])) {
            $this->items = unserialize($_SESSION['pkdebugbar']);
        }
    }

    /**
     * Save debug bar to session
     *
     * @return $this
     */
    public function save()
    {
        if ($this->has_session) {
            $_SESSION['pkdebugbar'] = serialize($this->items);
        }
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function mergeWith(array $data)
    {
        $this->items = array_merge($this->items, $data);
        return $this;
    }

    /**
     * Reset stored data
     *
     * @return $this
     */
    public function reset()
    {
        $this->items = [];

        if ($this->has_session) {
            $_SESSION['pkdebugbar'] = serialize([]);
        }

        return $this;
    }
}
