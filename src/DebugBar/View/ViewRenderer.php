<?php

namespace Peak\DebugBar\View;

/**
 * View File Renderer
 */
class ViewRenderer
{
    /**
     * Render a view file
     *
     * @param string $file
     * @param mixed $view
     * @param null|string $content
     * @return null|string
     * @throws ViewNotFoundException
     */
    public function renderContent($file, $view, $content = null)
    {
        if (!file_exists($file)) {
            throw new ViewNotFoundException($file);
        }
        ob_start();
        include $file;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
