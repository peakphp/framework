<?php

namespace Peak\View\Helper;

use Peak\View\Helper;

/**
 * Assets url helpers for files like css/js/img
 */
class Assets extends Helper
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
     * Init the class and set default assets path and base url optionnaly
     *
     * @param   string|null $path
     */
    public function __construct($path = null, $url = null)
    {
        parent::__construct();

        if (isset($path)) {
            $this->setPath($path);
        } else {
            $this->setPath('assets');
        }

        if (isset($url)) {
            $this->setUrl($url);
        } else {
            $this->setUrl(url());
        }
    }

    /**
     * Delegate type method/args to process()
     *
     * @example ('css', array('theme/css/myfile1.css', ...)) will call method _asset_css() with the file(s) path(s)
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
     * Check if javascript file exists
     *
     * @param  string $file
     * @return bool
     */
    public function exists($file)
    {
        $filepath = $this->assets_path.'/'.$file;
        return file_exists($filepath);
    }

    /**
     * Proccess a single or a bunch of assets file
     *
     * @param  string        $type
     * @param  array|string  $paths
     * @param  string|null   $param add url param if specified
     * @return string
     */
    public function process($type, $paths, $param = null)
    {
        $output = '';
        $mtype  = '_asset_'.$type;

        // force paths to be an array
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        if (empty($paths)) {
            return;
        }

        // if asset type doesn't exists
        if (!method_exists($this, $mtype)) {
            // if type is auto, we will retreive asset based on file extension if asset method exists
            if (in_array($type, array('auto', 'auto-detect', 'autodetect'))) {
                foreach ($paths as $p) {
                    $ext = '_asset_'.pathinfo($p, PATHINFO_EXTENSION);
                    if (method_exists($this, $ext)) {
                        $output .= $this->$ext($p, $param);
                    }
                }
            } else {
                return;
            }
        } else {
            foreach ($paths as $p) {
                $output .= $this->$mtype($p, $param);
            }
        }

        return $output;
    }

    /**
     * The url for the assets.
     *
     * @param  string $filepath
     * @return string
     */
    protected function _asset_url($filepath)
    {
        return $this->assets_base_url.'/'.$this->assets_path.'/'.$filepath;
    }

    /**
     * Javascript <script> tag
     *
     * @param  string $path
     * @return string
     */
    protected function _asset_js($filepath, $param = null)
    {
        $url = $this->_asset_url($filepath).((isset($param)) ? '?'.$param : '');
        return '<script type="text/javascript" src="'.$url.'"></script>';
    }

    /**
     * Stylesheet <link> tag
     *
     * @param  string $path
     * @return string
     */
    protected function _asset_css($filepath, $param = null)
    {
        $url = $this->_asset_url($filepath).((isset($param)) ? '?'.$param : '');
        return '<link rel="stylesheet" href="'.$url.'">';
    }
}
