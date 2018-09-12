<?php

declare(strict_types=1);

namespace Peak\DebugBar\Modules\Peak;

use Peak\DebugBar\AbstractModule;

/**
 * Class Peak
 * @package Peak\DebugBar\Modules\Peak
 */
class Peak extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        // nothing to do
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return '&nbsp;';
    }
}
