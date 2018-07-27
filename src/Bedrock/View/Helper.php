<?php

declare(strict_types=1);

namespace Peak\Bedrock\View;

use Peak\Bedrock\View;

/**
 * Class Helper
 * @package Peak\Bedrock\View
 */
abstract class Helper
{
    /**
     * View instance
     * @var \Peak\Bedrock\View
     */
    public $view;

    /**
     * Helper constructor.
     *
     * @param View $view
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }
}
