<?php

namespace Peak\DebugBar\View;

use Peak\Common\Collection;
use Peak\Common\Interfaces\Renderable;

/**
 * Layout View
 */
class Layout extends View implements Renderable
{
    /**
     * @var string
     */
    protected $content;

    /**
     * Layout constructor
     *
     * @param string $file
     * @param array $view_data
     * @throws \Exception
     */
    public function __construct($file, Collection $view_data = null, $content = null)
    {
        // stock view data
        parent::__construct($file, $view_data);

        // stock layout content
        $this->content = $content;
    }

    /**
     * Render view with vars
     *
     * @return null|string
     * @throws ViewNotFoundException
     */
    public function render()
    {
        // render the view file
        return (new ViewRenderer())->renderContent(
            $this->file,
            $this->toObject(),
            $this->content
        );
    }
}
