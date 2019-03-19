<?php

declare(strict_types=1);

namespace Peak\View\Helper;

use function str_ireplace;

class BaseUrl
{
    /**
     * @var array
     */
    private $server = [];

    /**
     * @var string
     */
    private $pathPrefix;

    /**
     * @var bool
     */
    private $protocol = false;

    /**
     * @var bool
     */
    private $useForwardedHost = false;

    /**
     * @var bool
     */
    private $ignorePort = false;

    /**
     * BaseUrl constructor.
     * @param array $server
     * @param string $pathPrefix
     */
    public function __construct(array $server, string $pathPrefix = '')
    {
        $this->server = $server;
        $this->pathPrefix = $pathPrefix;
    }

    /**
     * @param string $path
     * @param bool $protocol
     * @param bool $useForwardedHost
     * @return string
     */
    public function __invoke(string $path = '', bool $protocol = null, bool $useForwardedHost = null): string
    {
        $protocol = $protocol ?? $this->protocol;
        $useForwardedHost = $useForwardedHost ?? $this->useForwardedHost;

        $sp = ($this->isSSL()) ? 'https' : 'http';

        $port = $this->getPort();
        if ($this->ignorePort) {
            $port = '';
        }

        $host = $this->getHost($useForwardedHost);
        $host = isset($host) ? $host : $this->server['SERVER_NAME'] . $port;

        if (!empty($path)) {
            $path = '/' . $path;
        }
        $url = '//' . str_ireplace('//', '/', $host . $this->pathPrefix . $path);
        if ($protocol) {
            $url = $sp.':'.$url;
        }
        return $url;
    }

    /**
     * @param bool $vale
     * @return $this
     */
    public function addProtocolByDefault(bool $value)
    {
        $this->protocol = $value;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function useForwardedHostByDefault(bool $value)
    {
        $this->useForwardedHost = $value;
        return $this;
    }

    /**
     * @param bool $value
     * @return $this
     */
    public function ignorePort(bool $value)
    {
        $this->ignorePort = $value;
        return $this;
    }

    /**
     * @param bool|null $useForwardedHost
     * @return string|null
     */
    private function getHost(bool $useForwardedHost = null)
    {
        $host = null;
        if ($useForwardedHost && isset($this->server['HTTP_X_FORWARDED_HOST'])) {
            $host = $this->server['HTTP_X_FORWARDED_HOST'];
        } elseif (isset($this->server['HTTP_HOST'])) {
            $host = $this->server['HTTP_HOST'];

        } elseif (isset($this->server['HOST'])) {
            $host = $this->server['HOST'];
        }
        return $host;
    }

    /**
     * @return string
     */
    private function getPort(): string
    {
        $port = $this->server['SERVER_PORT'] ?? 80;
        return in_array($port, [80, 443]) ? '' : ':'.$port;
    }

    /**
     * @return bool
     */
    private function isSSL(): bool
    {
        return (isset($this->server['HTTPS']) && !empty($this->server['HTTPS']) && $this->server['HTTPS'] == 'on');
    }
}
