<?php

declare(strict_types=1);

namespace Peak\View;

use Peak\Common\Traits\Macro;
use Peak\View\Exception\FileNotFoundException;

/**
 * Class View
 * @package Peak\View
 */
class View
{
    use Macro;

    /**
     * @var array
     */
    private $templateSources;

    /**
     * @var array
     */
    private $vars = [];

    /**
     * @var array
     */
    private $helpers = [];

    /**
     * @var string
     */
    private $layoutContent;

    /**
     * @var Presentation
     */
    private $presentation;

    /**
     * View constructor.
     * @param array $vars
     * @param array $templateSources
     */
    public function __construct(?array $vars, Presentation $presentation)
    {
        if (isset($vars)) {
            $this->vars = $vars;
        }
        $this->presentation = $presentation;
    }

    /**
     * @param string $var
     * @return mixed
     * @throws \Exception
     */
    public function &__get(string $var)
    {
        if (!array_key_exists($var, $this->vars)) {
            throw new \Exception('variable '.$var.' not found');
        }

        return $this->vars[$var];
    }

    /**
     * @param string $var
     * @return bool
     */
    public function __isset(string $var)
    {
        return array_key_exists($var, $this->vars);
    }

    /**
     * Call a macro or helper in that order
     * @param string $macroName
     * @param array $args
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        if ($this->hasMacro($method)) {
            return $this->callMacro($method, $args);
        } elseif(isset($this->helpers[$method])) {
            return call_user_func_array($this->helpers[$method], $args);
        }

        return new \RuntimeException('No macro or helper found for "'.$method.'"');
    }

    /**
     * @param array $helpers
     */
    public function setHelpers(array $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @throws \Exception
     */
    public function render()
    {
        ob_start();
        $this->recursiveRender($this->presentation->getSources());
        return ob_get_clean();
    }

    /**
     * @param array $templateSources
     * @throws \Exception
     */
    private function recursiveRender(array $templateSources)
    {
        foreach ($templateSources as $layout => $source) {
            if (is_array($source)) {
                ob_start();
                $this->recursiveRender($source);
                $this->layoutContent = ob_get_clean();
                $this->renderFile($layout);
                continue;
            }

            $this->renderFile($source);
        }
    }

    /**
     * @param string $file
     * @throws FileNotFoundException
     */
    private function renderFile(string $file)
    {
        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }
        include $file;
    }
}

