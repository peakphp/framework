<?php

declare(strict_types=1);

namespace Peak\View;

use Peak\Blueprint\View\Presentation;
use Peak\Common\Traits\Macro;
use Peak\View\Exception\FileNotFoundException;
use Peak\View\Exception\VarNotFoundException;

use function array_key_exists;
use function call_user_func_array;
use function file_exists;
use function is_array;
use function ob_end_clean;
use function ob_start;
use function ob_get_clean;


class View implements \Peak\Blueprint\View\View
{
    use Macro;

    /**
     * @var array
     */
    protected $vars = [];

    /**
     * @var array
     */
    protected $helpers = [];

    /**
     * @var string|false
     */
    protected $layoutContent;

    /**
     * @var Presentation
     */
    protected $presentation;

    /**
     * @var int
     */
    protected $obN = 0;

    /**
     * View constructor.
     * @param array|null $vars
     * @param Presentation $presentation
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
            throw new VarNotFoundException($var);
        }

        return $this->vars[$var];
    }

    /**
     * @param string $var
     * @return bool
     */
    public function __isset(string $var): bool
    {
        return array_key_exists($var, $this->vars);
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return Presentation
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * Call a macro or helper in that order
     *
     * @param string $method
     * @param array $args
     * @return mixed
     * @throw \RuntimeException
     */
    public function __call(string $method, array $args)
    {
        if ($this->hasMacro($method)) {
            return $this->callMacro($method, $args);
        } elseif(isset($this->helpers[$method])) {
            return call_user_func_array($this->helpers[$method], $args);
        }

        throw new \RuntimeException('No macro or helper found for "'.$method.'"');
    }

    /**
     * @param array $helpers
     */
    public function setHelpers(array $helpers)
    {
        $this->helpers = $helpers;
    }

    /**
     * @return string|false
     * @throws FileNotFoundException
     */
    public function render()
    {
        $this->obN++;
        ob_start();
        $this->recursiveRender($this->presentation->getSources());
        $this->obN--;
        return ob_get_clean();
    }

    /**
     * @param array $templateSources
     * @throws FileNotFoundException
     */
    protected function recursiveRender(array $templateSources)
    {
        foreach ($templateSources as $layout => $source) {
            if (is_array($source)) {
                $this->obN++;
                ob_start();
                $this->recursiveRender($source);
                $this->obN--;
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
    protected function renderFile(string $file)
    {
        if (!file_exists($file)) {
            // make sure we close all out
            while ($this->obN > 0) {
                ob_end_clean();
                $this->obN--;
            }
            throw new FileNotFoundException($file);
        }
        include $file;
    }
}
