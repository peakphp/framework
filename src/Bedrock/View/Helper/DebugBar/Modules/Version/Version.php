<?php

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\Version;

use Peak\Bedrock\Application\Kernel;
use Peak\DebugBar\AbstractModule;

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
