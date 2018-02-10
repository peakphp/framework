<?php

namespace Peak\DebugBar;

use Peak\Common\Session;
use Peak\Common\Collection;

class SessionData extends Collection
{
    /**
     * @var array
     */
    protected $default_data = [
        'chronometers' => []
    ];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * ArrayTable constructor.
     */
    public function __construct()
    {
        if (Session::isStarted()) {
            $this->load();
        }
    }

    /**
     * Get data
     *
     * @return array
     */
    public function get()
    {
        return $this->data;
    }

    /**
     * Load session pkdebugbar
     */
    protected function load()
    {
        $this->data = [];
        if (isset($_SESSION['pkdebugbar'])) {
            $this->data = unserialize($_SESSION['pkdebugbar']);
        } else {
            $this->data = $this->default_data;
            $this->save($this->data);
        }
    }

    /**
     * Save debug bar data to session
     *
     * @param array $data
     * @return $this
     */
    public function save(array $data)
    {
        $_SESSION['pkdebugbar'] = serialize($data);
        $this->load();
        return $this;
    }
}
