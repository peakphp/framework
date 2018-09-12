<?php

namespace Peak\DebugBar;

class SessionStorage extends AbstractStorage
{
    /**
     * @var bool
     */
    protected $hasSession = false;

    /**
     * SessionData constructor.
     */
    public function __construct()
    {
        $this->hasSession = $this->isStarted();
        parent::__construct();
    }

    protected function isStarted(): bool
    {
        return (session_status() == PHP_SESSION_ACTIVE);
    }

    /**
     * Load session pkdebugbar
     */
    protected function load()
    {
        if ($this->hasSession && isset($_SESSION['pkdebugbar'])) {
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
        if ($this->hasSession) {
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

        if ($this->hasSession) {
            $_SESSION['pkdebugbar'] = serialize([]);
        }

        return $this;
    }
}
