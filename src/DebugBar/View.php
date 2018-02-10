<?php

namespace Peak\Debugbar;

use Peak\Common\Collection;
use Peak\Common\Interfaces\Renderable;

/**
 * View
 */
class View extends Collection implements Renderable
{
    /**
     * View file
     * @var string
     */
    public $file;

    /**
     * View constructor
     *
     * @param string $file
     * @param array $view_data
     * @throws \Exception
     */
    public function __construct($file, $view_data = [])
    {
        if (!file_exists($file)) {
            throw new \Exception('View file '.$file.' not found!');
        }

        $this->file = $file;

        // stock view data
        parent::__construct($view_data);

        // lock view the data
        $this->readOnly();
    }

    /**
     * Render view with vars
     *
     * @return string
     */
    public function render()
    {
        ob_start();
        include $this->file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * Render the layout with the content
     *
     * @param $content
     * @return string
     */
    public function renderLayout($content, $tabs)
    {
        $layout_file = $this->file;

        // isolate layout file from this class
        $closure = function($content, $tabs) use ($layout_file) {
            ob_start();
            include $layout_file;
            $return = ob_get_contents();
            ob_end_clean();
            return $return;
        };

        return $closure($content, $tabs);
    }
}
