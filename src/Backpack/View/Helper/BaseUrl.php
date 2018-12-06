<?php

namespace Peak\Backpack\View\Helper;

use Peak\Blueprint\Bedrock\Application;

/**
 * Class BaseUrl
 * @package Peak\Backpack\View\Helper
 */
class BaseUrl
{
    /**
     * @var string
     */
    private $publicPath;

    /**
     * BaseUrl constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->server = $s = filter_var_array($_SERVER);
        if ($app->hasProp('view.base_url')) {
            $this->publicPath = relativePath($app->getProp('view.base_url'));
        }
    }

    /**
     * @param string $path
     * @param bool $use_forwarded_host
     * @param bool $protocol
     * @return string
     */
    public function __invoke($path = '/', $use_forwarded_host = true, $protocol = false)
    {
        $ssl = (!empty($this->server['HTTPS']) && $this->server['HTTPS'] == 'on');
        $sp = strtolower($this->server['SERVER_PROTOCOL']);
        //$protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $this->server['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;

        $host = null;
        if ($use_forwarded_host && isset($this->server['HTTP_X_FORWARDED_HOST'])) {
            $host = $this->server['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($this->server['HTTP_HOST'])) {
            $host = $this->server['HTTP_HOST'];
        }

        //$host = ($use_forwarded_host && isset($this->server['HTTP_X_FORWARDED_HOST'])) ? $this->server['HTTP_X_FORWARDED_HOST'] : (isset($this->server['HTTP_HOST']) ? $this->server['HTTP_HOST'] : null);

        $host = isset($host) ? $host : $this->server['SERVER_NAME'] . $port;
        return '//' . str_ireplace('//', '/', $host . $this->publicPath . '/' . $path);
    }
}