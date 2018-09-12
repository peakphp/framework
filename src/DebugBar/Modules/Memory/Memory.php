<?php

namespace Peak\DebugBar\Modules\Memory;

use Peak\DebugBar\AbstractModule;

class Memory extends AbstractModule
{
    protected $mem;

    /**
     * Initialize block
     */
    public function initialize()
    {
        $this->mem = formatSize(memory_get_peak_usage(true));
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return $this->mem;
    }
}
