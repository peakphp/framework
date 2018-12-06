<?php

namespace Peak\Backpack\View;

use Peak\View\HelperResolver;
use Peak\View\View;

/**
 * Class ViewBuilder
 * @package Peak\Backpack
 */
class ViewBuilder
{
    /**
     * @var array
     */
    private $vars = [];

    /**
     * @var array
     */
    private $templatesSources = [];

    /**
     * @var array
     */
    private $macros = [];

    /**
     * @var array
     */
    private $helpers = [];

    /**
     * @var HelperResolver
     */
    private $helperResolver;

    /**
     * ViewBuilder constructor.
     * @param HelperResolver|null $helperResolver
     */
    public function __construct(HelperResolver $helperResolver = null)
    {
        $this->helperResolver = $helperResolver ?? new HelperResolver(null);
    }

    /**
     * @param mixed $templatesSources
     */
    public function setTemplatesSources($templatesSources)
    {
        $this->templatesSources = $templatesSources;
        return $this;
    }

    /**
     * @param mixed $macros
     */
    public function setMacros(array $macros)
    {
        $this->macros = $macros;
        return $this;
    }

    /**
     * @param string $name
     * @param $macro
     * @return $this
     */
    public function setMacro(string $name, $macro)
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
     * @param $helper
     * @return $this
     */
    public function setHelper(string $name, $helper)
    {
        $this->helpers[$name] = $helper;
        return $this;
    }

    /**
     * @param mixed $vars
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
        return $this;
    }

    /**
     * @return View
     * @throws \Peak\View\Exception\InvalidHelperException
     * @throws \ReflectionException
     */
    public function build(): View
    {
        $view = new View($this->vars, $this->templatesSources);

        foreach ($this->helpers as $helperName => $helper) {
            if (isset($this->helperResolver)) {
                $helper = $this->helperResolver->resolve($helper);
            }
            $this->helpers[$helperName] = $helper;
        }

        $view->setHelpers($this->helpers);

        foreach ($this->macros as $macroName => $macro) {
            $view->addMacro($macroName, $macro);
        }

        return $view;
    }

    /**
     * @return string
     * @throws \Peak\View\Exception\InvalidHelperException
     * @throws \ReflectionException
     */
    public function render(): string
    {
        return $this->build()->render();
    }
}