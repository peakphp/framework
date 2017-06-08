<?php

namespace Peak\Bedrock;

use Peak\Bedrock\Application;
use Peak\Bedrock\View\Header;
use Peak\Common\ClassFinder;
use \Exception;

class View
{
    /**
     * view vars
     * @var array
     */
    protected $vars = [];

    /**
     * view helpers objects
     * @var array
     */
    private $helpers = [];

    /**
     * view header object
     * @var object
     */
    private $header;

    /**
     * view rendering object
     * @var object
     */
    private $engine;
    
    /**
     * Determine if view will be rendered(view engine executed)
     * @var bool
     */
    private $render = true;

    /**
     * Constructor
     */
    public function __construct(array $vars = [])
    {
        $this->vars = $vars;
    }

    /**
     * Set/overwrite view variable
     *
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value = null)
    {
        $this->vars[$name] = $value;
    }

    /**
     * Get view variable
     *
     * @param  string $name
     * @return mixed
     */
    public function &__get($name)
    {
        if (isset($this->vars[$name])) {
            return $this->vars[$name];
        }
        return ${null};
    }

    /**
     * Isset $vars keyname
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        return array_key_exists($name, $this->vars) ? true : false;
    }

    /**
     * Unset $vars keyname
     *
     * @param string $name
     */
    public function __unset($name)
    {
        if (array_key_exists($name, $this->vars)) {
            unset($this->vars[$name]);
        }
    }

    /**
     * We try to call View Render Engine object method.
     * If not, we try to return a helper object based on $method name.
     * So every Rendering Engine Method can be called directly inside Peak\Bedrock\View and
     * every instantiated Peak_View_Helpers
     *
     * @param  string $method
     * @param  array  $args
     * @return mixed
     */
    public function __call($method, $args = null)
    {
        if (method_exists($this->engine(), $method)) {
            return call_user_func_array([$this->engine(), $method], $args);
        } else {
            return $this->helper($method, $args);
        }
    }

    /**
     * Set/overwrite view variable
     *
     * @see    __set()
     * @return $this
     */
    public function set($name, $value = null)
    {
        $this->__set($name, $value);
        return $this;
    }

    /**
     * Count template variables
     *
     * @return integer
     */
    public function countVars()
    {
        return count($this->getVars());
    }

    /**
     * Get template variables
     *
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * Set/Overwrite some view vars
     *
     * @param array $vars
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    /**
     * Set/Add some view vars
     * Existing var key name will be overwritten, otherwise var is added to current $vars
     */
    public function addVars($vars)
    {
        foreach ($vars as $k => $v) {
            $this->set($k, $v);
        }
    }

    /**
     * Clean all variable in $vars
     */
    public function resetVars()
    {
        $this->vars = [];
    }

    /**
     * Set/Get current view rendering engine object
     *
     * @param  string $engine_name
     * @return object Peak_View_Render_*
     */
    public function engine($engine_name = null)
    {
        if (isset($engine_name)) {
            $engine_name = strip_tags(ucfirst($engine_name));
            $engine_class = 'Peak\Bedrock\View\Render\\'.$engine_name;
            if (!class_exists($engine_class)) {
                throw new Exception('View rendering engine '.$engine_name.' not found');
            }
            $this->engine = Application::instantiate($engine_class);
        }

        return $this->engine;
    }

    /**
     * Get render engine name
     *
     * @return string
     */
    public function getEngineName()
    {
        if (is_object($this->engine)) {
            return strtolower(str_replace('Peak\Bedrock\View\Render\\', '', get_class($this->engine)));
        }
        return null;
    }
    
    /**
     * Return render option
     *
     * @return bool
     */
    public function canRender()
    {
        return $this->render;
    }

    /**
     * Disable rendering
     *
     * @return $this
     */
    public function disableRender()
    {
        $this->render = false;
        return $this;
    }
    
    /**
     * Enabled rendering
     *
     * @return $this
     */
    public function enableRender()
    {
        $this->render = true;
        return $this;
    }

    /**
     * Render Controller Action View file with the current rendering engine
     *
     * @param  string $file
     * @param  string $path
     * @return string or array   return array of view files when layout is used
     */
    public function render($file, $path = null)
    {
        //skip render part(see $render)
        if ($this->render === false) {
            return;
        }
        // check if engine is set
        if (!is_object($this->engine)) {
            throw new Exception('View rendering engine not set');
        }
        // check if we got http header
        if (is_object($this->header)) {
            $this->header->release();
        }

        $this->engine()->render($file, $path);
    }

    /**
     * Load/get HTTP header object
     *
     * @return object \Peak\Bedrock\View\Header
     */
    public function header()
    {
        if (!is_object($this->header)) {
            $this->header = new Header();
        }

        return $this->header;
    }

    /**
     * Load helpers objects method and return helper obj
     *
     * @params string $name
     * @params array  $params
     * @return mixed
     */
    public function helper($name = null, $params = [])
    {
        if (array_key_exists($name, $this->helpers)) {
            return $this->helpers[$name];
        }

        $helper = (new ClassFinder([
            'Peak\Bedrock\View\Helper',
            Application::conf('ns').'\Views\Helpers'
        ]))->findLast(ucfirst($name));

        if ($helper === null) {
            return trigger_error('View helper '.$name.' doesn\'t exists');
        }

        $this->helpers[$name] = Application::instantiate($helper, $params);
        return $this->helpers[$name];
    }
}
