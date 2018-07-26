<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\Version;

use Peak\Bedrock\Application\Kernel;
use Peak\DebugBar\AbstractModule;

/**
 * Class Version
 * @package Peak\Bedrock\View\Helper\DebugBar\Modules\Version
 */
class Version extends AbstractModule
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
        return '<b>Peak v'.Kernel::VERSION.' / PHP '.phpversion().'</b>';
    }
}
