<?php

namespace Peak\DebugBar\Modules\UserConstants;

use Peak\DebugBar\AbstractModule;

class UserConstants extends AbstractModule
{

    protected $use_default_logo = true;

    /**
     * Initialize block
     */
    public function initialize()
    {
        $constants = get_defined_constants(true);
        $this->data->constants = $constants['user'];
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'User Constants';
    }
}
