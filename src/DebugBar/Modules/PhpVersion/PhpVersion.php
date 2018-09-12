<?php

declare(strict_types=1);

namespace Peak\DebugBar\Modules\PhpVersion;

use Peak\DebugBar\AbstractModule;

/**
 * Class PhpVersion
 * @package Peak\DebugBar\Modules\PhpVersion
 */
class PhpVersion extends AbstractModule
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
        return '<b>PHP '.phpversion().'</b>';
    }
}
