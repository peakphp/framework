<?php

namespace Peak\DebugBar;

use Peak\Common\Session;

class SessionStorage extends AbstractStorage
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
        parent::__construct();
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
