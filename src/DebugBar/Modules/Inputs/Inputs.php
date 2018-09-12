<?php

namespace Peak\DebugBar\Modules\Inputs;

use Peak\DebugBar\AbstractModule;

class Inputs extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        $this->data->get = filter_input_array(INPUT_GET);
        $this->data->post = filter_input_array(INPUT_POST);
        $this->data->cookie = filter_input_array(INPUT_COOKIE);
        $this->data->server = filter_input_array(INPUT_SERVER);
        $this->data->env = filter_input_array(INPUT_ENV);
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'Inputs';
    }
}
