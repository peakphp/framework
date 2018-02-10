<?php

namespace Peak\Bedrock\View\Helper\DebugBar\Modules\ViewVars;

use Peak\Bedrock\Application;
use Peak\Bedrock\View;
use Peak\DebugBar\AbstractModule;

class ViewVars extends AbstractModule
{
    /**
     * Initialize block
     */
    public function initialize()
    {
        $view = Application::get(View::class);
        $vars = $view->getVars();
        $this->data->count = count($vars);
        $this->data->vars = htmlentities(print_r($vars, true));
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return 'View';
    }
}
