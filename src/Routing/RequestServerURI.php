<?php

namespace Peak\Routing;

use Peak\Routing\Request;

class RequestServerURI extends Request
{
    /**
     * Overload Request constructor using $_SERVER request uri
     */
    public function __construct($base_uri = null)
    {
        parent::__construct(
            filter_input(INPUT_SERVER, 'REQUEST_URI'),
            $base_uri
        );
    }
}
