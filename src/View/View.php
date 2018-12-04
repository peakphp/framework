<?php

namespace Peak\View;

use \Peak\Common\Traits\Injectable;

/**
 * Class View
 * @package Peak\View
 */
class View
{
    use Injectable;

    /**
     * @var array
     */
    private $data;

    /**
     * @var array
     */
    private $templateSources;

    /**
     * @var
     */
    private $parent;

    /**
     * @var array
     */
    private $vars;

    /**
     * View constructor.
     * @param $data
     * @param $sources
     */
    public function __construct(array $vars, array $templateSources)
    {
        $this->vars = $vars;
        $this->templateSources = $templateSources;
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
     * @throws \Exception
     */
    public function render()
    {
        ob_start();
        $this->recursiveRender($this->templateSources);
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
                $this->childContent = ob_get_clean();
                $this->renderFile($layout);
                continue;
            }

            $this->renderFile($source);
        }
    }

    /**
     * @param string $file
     * @throws \Exception
     */
    private function renderFile(string $file)
    {
        if (!file_exists($file)) {
            throw new \Exception('View source file "'.$file.'" not found');
        }
        include $file;
    }
}

