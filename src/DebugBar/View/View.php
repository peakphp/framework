<?php

namespace Peak\DebugBar\View;

use Peak\Common\Collection\Collection;
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
    protected $file;

    /**
     * View constructor
     *
     * @param string $file
     * @param Collection $view_data
     * @throws \Exception
     */
    public function __construct($file, Collection $view_data = null)
    {
        $this->file = $file;

        // stock view data
        parent::__construct($view_data->toArray());

        // lock view the data
        $this->readOnly();
    }

    /**
     * Render view with vars
     *
     * @return string
     * @throws ViewNotFoundException
     */
    public function render()
    {
        // render the view file
        return (new ViewRenderer())->renderContent(
            $this->file,
            $this->toObject()
        );
    }
}
