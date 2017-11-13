<?php

namespace Peak\Bedrock\View;

use Peak\Bedrock\View;
use Peak\Common\Collection;
use Peak\Bedrock\View\Exceptions\BlockNotFoundException;

/**
 * View renderable block file
 */
class Block extends Collection implements RenderableInterface
{
    /**
     * View instance
     * @var \Peak\Bedrock\View
     */
    public $view;

    /**
     * Block file in app views
     * @var string
     */
    public $block_file;

    /**
     * Get view object
     */
    public function __construct(View $view, $block_file, $block_data)
    {
        $this->view = $view;
        $this->block_file = config('path.apptree.views').'/'.$block_file;

        if (!file_exists($this->block_file)) {
            throw new BlockNotFoundException($block_file);
        }

        // stock block data
        parent::__construct($block_data);

        // lock the block data
        $this->readOnly();
    }

    /**
     * Render a block with vars
     * @param array $vars
     */
    public function render()
    {
        include $this->block_file;
    }
}
