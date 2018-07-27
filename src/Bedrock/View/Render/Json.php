<?php

declare(strict_types=1);

namespace Peak\Bedrock\View\Render;

use Peak\Bedrock\View\Render;

/**
 * Class Json
 * @package Peak\Bedrock\View\Render
 */
class Json extends Render
{
    /**
     * Render view(s)
     *
     * @param $file
     * @param null $path
     * @throws \Peak\Bedrock\Application\Exceptions\InstanceNotFoundException
     * @throws \Peak\Bedrock\Application\Exceptions\MissingContainerException
     */
    public function render($file, $path = null)
    {
        $this->scripts_file = $file;
        $this->scripts_path = $path;

        $viewvars = $this->view->getVars();

        header('Content-Type: application/json');
        
        $json = json_encode($viewvars);

        $this->preOutput($json);
    }
    
    /**
     * Output Json
     *
     * @param string $json
     */
    protected function output($json)
    {
        echo $json;
    }
}
