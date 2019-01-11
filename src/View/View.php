<?php

declare(strict_types=1);

namespace Peak\View;

use Peak\Common\Traits\Macro;
use Peak\View\Exception\FileNotFoundException;

class View implements \Peak\Blueprint\View\View
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
            throw new \Exception('variable '.$var.' not found');
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
     * @param string $method
     * @param array $args
     * @return mixed|\RuntimeException
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
     * @return string
     * @throws FileNotFoundException
     */
    public function render(): string
    {
        ob_start();
        $this->recursiveRender($this->presentation->getSources());
        return ob_get_clean();
    }

    /**
     * @param array $templateSources
     * @throws FileNotFoundException
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
