<?php

namespace Peak\Bedrock\View\Helper;

use Peak\Bedrock\View\Helper;

/**
 * Assets url helpers for files like css/js/img
 */
class Assets
{
    /**
     * Assets end url path
     * @var string
     */
    private $assets_path;

    /**
     * Assets base url
     * @var string
     */
    private $assets_base_url = null;

    /**
     * Init the class and set default assets path and base url optionally
     *
     * @param string $path
     * @param string $url
     */
    public function __construct($path, $url)
    {
        $this->setPath($path);
        $this->setUrl($url);
    }

    /**
     * Delegate type method/args to process()
     *
     * @example ('css',  ['theme/css/myfile1.css', ...]) will call method assetCss() with the file(s) path(s)
     *
     * @param  string $method
     * @param  string $args
     * @return string
     */
    public function __call($method, $args)
    {
        if (array_key_exists(1, $args)) {
            return $this->process($method, $args[0], $args[1]);
        }

        return $this->process($method, $args[0]);
    }

    /**
     * Set assets path
     *
     * @param  string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->assets_path = $path;
        return $this;
    }

    /**
     * Get assets path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->assets_path;
    }

    /**
     * Set assets base url
     *
     * @param  string $url
     * @return $this
     */
    public function setUrl($url)
    {
        if (substr($url, -1, 1) === '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }
        $this->assets_base_url = $url;

        return $this;
    }

    /**
     * Get assets base url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->assets_base_url;
    }

    /**
     * Check if javascript file exists
     *
     * @param  string $file
     * @return bool
     */
    public function exists($file)
    {
        return file_exists($this->assets_path.'/'.$file);
    }

    /**
     * Process a single or a bunch of assets file
     *
     * @param  string        $type
     * @param  array|string  $paths
     * @param  string|null   $param add url param if specified
     * @return string
     */
    public function process($type, $paths, $param = null)
    {
        if (empty($paths)) {
            return;
        }

        // force paths to be an array
        if (!is_array($paths)) {
            $paths = [$paths];
        }

        $mtype  = 'asset'.ucfirst($type);
        // if asset type doesn't exists
        if (!method_exists($this, $mtype)) {
            $mtype = 'assetAuto';
        }

        $output = '';
        foreach ($paths as $p) {
            $output .= $this->$mtype($p, $param);
        }

        return $output;
    }

    /**
     * Autodetect asset based on file extension
     *
     * @param string $filepath
     * @param string $param
     * @return mixed
     */
    protected function assetAuto($filepath, $param = null)
    {
        $ext = 'asset'.ucfirst(pathinfo($filepath, PATHINFO_EXTENSION));
        if (method_exists($this, $ext)) {
            return $this->$ext($filepath, $param);
        }
    }

    /**
     * The url for the assets.
     *
     * @param  string $filepath
     * @return string
     */
    protected function assetUrl($filepath)
    {
        return $this->assets_base_url.'/'.$filepath;
    }

    /**
     * Javascript <script> tag
     *
     * @param  string $path
     * @return string
     */
    protected function assetJs($filepath, $param = null)
    {
        $url = $this->assetUrl($filepath).((isset($param)) ? '?'.$param : '');
        return '<script type="text/javascript" src="'.$url.'"></script>';
    }

    /**
     * Stylesheet <link> tag
     *
     * @param  string $path
     * @return string
     */
    protected function assetCss($filepath, $param = null)
    {
        $url = $this->assetUrl($filepath).((isset($param)) ? '?'.$param : '');
        return '<link rel="stylesheet" href="'.$url.'">';
    }
}
