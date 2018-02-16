<?php

namespace Peak\DebugBar\Modules\Session;

use Peak\DebugBar\AbstractModule;

class Session extends AbstractModule
{
    protected $use_default_logo = true;

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

        $this->data->infos = [
            'id' => session_id(),
            'name' => session_name(),
            'cache_expire' => session_cache_expire(),
            'cache_limiter' => session_cache_limiter(),
            'module_name' => session_module_name(),
            'save_path' => realpath(session_save_path()),
            'cookie_params' => session_get_cookie_params(),
        ];
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
