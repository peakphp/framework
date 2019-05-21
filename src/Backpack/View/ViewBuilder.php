<?php

declare(strict_types=1);

namespace Peak\Backpack\View;

use Closure;
use Peak\Blueprint\Common\ResourceResolver;
use Peak\Blueprint\View\Presentation;
use Peak\View\HelperResolver;
use Peak\View\View;

class ViewBuilder
{
    /**
     * @var array|null
     */
    private $vars = null;

    /**
     * @var Presentation
     */
    private $presentation;

    /**
     * @var array
     */
    private $macros = [];

    /**
     * @var array
     */
    private $helpers = [];

    /**
     * @var HelperResolver|null
     */
    private $helperResolver;

    /**
     * ViewBuilder constructor.
     * @param ResourceResolver|null $helperResolver
     */
    public function __construct(ResourceResolver $helperResolver = null)
    {
        $this->helperResolver = $helperResolver ?? new HelperResolver(null);
    }

    /**
     * @param Presentation $presentation
     * @return $this
     */
    public function setPresentation(Presentation $presentation)
    {
        $this->presentation = $presentation;
        return $this;
    }

    /**
     * @param array $macros
     * @return $this
     */
    public function setMacros(array $macros)
    {
        $this->macros = $macros;
        return $this;
    }

    /**
     * @param string $name
     * @param Closure $macro
     * @return $this
     */
    public function setMacro(string $name, Closure $macro)
    {
        $this->macros[$name] = $macro;
        return $this;
    }

    /**
     * @param array $helpers
     * @return $this
     */
    public function setHelpers(array $helpers)
    {
        $this->helpers = $helpers;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $helper
     * @return $this
     */
    public function setHelper(string $name, $helper)
    {
        $this->helpers[$name] = $helper;
        return $this;
    }

    /**
     * @param array|null $vars
     * @return $this
     */
    public function setVars(?array $vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @param array $vars
     * @return $this
     */
    public function addVars(array $vars)
    {
        if (!is_array($this->vars)) {
            $this->vars = [];
        }

        $this->vars = array_merge($this->vars, $vars);
        return $this;
    }

    /**
     * @return View
     * @throws \Peak\View\Exception\InvalidHelperException
     * @throws \Exception
     */
    public function build(): View
    {
        if (!isset($this->presentation)) {
            throw new \Exception('A presentation is required in order to create a view');
        }

        $view = new View($this->vars, $this->presentation);

        foreach ($this->helpers as $helperName => $helper) {
            if (isset($this->helperResolver)) {
                $helper = $this->helperResolver->resolve($helper);
            }
            $this->helpers[$helperName] = $helper;
        }

        $view->setHelpers($this->helpers);

        foreach ($this->macros as $macroName => $macro) {
            $view->setMacro($macroName, $macro);
        }

        return $view;
    }

    /**
     * @return false|string
     * @throws \Peak\Di\Exception\ClassDefinitionNotFoundException
     * @throws \Peak\View\Exception\FileNotFoundException
     * @throws \Peak\View\Exception\InvalidHelperException
     * @throws \ReflectionException
     */
    public function render()
    {
        return $this->build()->render();
    }
}