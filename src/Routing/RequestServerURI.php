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
        $request_uri = isCli() ? '' : filter_var($_SERVER['REQUEST_URI']);
        parent::__construct($request_uri, $base_uri);
    }
}
