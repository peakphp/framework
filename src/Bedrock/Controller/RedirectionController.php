<?php

namespace Peak\Bedrock\Controller;

use Peak\Bedrock\Application\Config;
use Peak\Bedrock\Application\Routing;
use Peak\Bedrock\View;

abstract class RedirectionController extends ActionController
{
    /**
     * @var array
     */
    protected $redirects = [];

    /**
     * @var Routing
     */
    protected $routing;

    /**
     * RedirectController constructor.
     * @param View $view
     * @param Config $config
     */
    public function __construct(View $view, Config $config, Routing $routing)
    {
        parent::__construct($view, $config);
        if (isset($config->redirects)) {
            $this->redirects = $config->redirects;
        }
        $this->routing = $routing;
    }

    /**
     * Process the redirect
     */
    public function preAction()
    {
        $redirect_id = substr($this->action, 1);

        if (!isset($this->redirects[$redirect_id])) {
            $this->redirect('error');
            return;
        }

        $redirect = $this->redirects[$redirect_id];

        $code = null;
        if (isset($redirect['code'])) {
            $code = $redirect['code'];
        }

        $method = $this->action;
        if (method_exists($this, $method)) {
            $this->$method();
        }

        $destination = $this->getRealDestination($redirect['destination']);
        $this->redirectUrl($destination, $code);
    }

    /**
     * @param $destination
     */
    protected function getRealDestination($destination)
    {
        if (preg_match_all('#\{([a-zA-Z0-9_-]+)\}#', $destination, $matches)) {
            foreach ($matches[1] as $index => $param) {
                if (!($this->params->has($param))) {
                    throw new \Exception('Route redirect for "'.$this->routing->request->raw_uri.'" can\'t be satisfied');
                }
                $destination = str_ireplace($matches[0][$index], $this->params->$param, $destination);
            }
        }
        return $destination;
    }
}
