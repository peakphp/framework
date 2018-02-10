<?php

namespace Peak\DebugBar\Modules\Session;

use Peak\DebugBar\AbstractModule;

class Session extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        if (!isset($_SESSION)) {
            $this->disableRender();
            return;
        }

        $this->data->session = filter_var_array($_SESSION);
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'Session';
    }
}
