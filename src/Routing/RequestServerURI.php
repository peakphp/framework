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
        $uri = parse_url($_SERVER['REQUEST_URI']);
        $request_uri = isCli() ? '' : filter_var($uri['path']);
        parent::__construct($request_uri, $base_uri);
    }
}
