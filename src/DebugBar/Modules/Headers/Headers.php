<?php

namespace Peak\DebugBar\Modules\Headers;

use Peak\DebugBar\AbstractModule;

class Headers extends AbstractModule
{
    /**
     * @var bool
     */
    protected $use_default_logo = true;

    /**
     * Initialize module
     */
    public function initialize()
    {
        $headers_assoc = [];
        $headers = headers_list();

        foreach ($headers as $header) {
            $header_parts = explode(':', $header);
            $headers_assoc[array_shift($header_parts)] = implode(':', $header_parts);
        }

        $this->data->headers = $headers_assoc;
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'Headers';
    }
}
