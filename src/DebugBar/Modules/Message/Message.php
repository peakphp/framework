<?php

namespace Peak\DebugBar\Modules\Message;

use Peak\DebugBar\AbstractModule;

class Message extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        // nothing to do
        $this->data->messages = [];
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'Messages <sup>'.count($this->data->messages).'</sup>';
    }
}
