<?php

namespace Peak\DebugBar\Modules\Files;

use Peak\DebugBar\AbstractModule;

class Files extends AbstractModule
{
    /**
     * @var array
     */
    protected $files = [];

    /**
     * Initialize block
     */
    public function initialize()
    {
        $root_abspath = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
        $temp = get_included_files();
        $files = [
            'files' => [],
            'total_size' => 0,
            'count' => 0,
            'basepath' => '',
        ];
        foreach ($temp as $file) {

            $data = [
                'size' => filesize($file),
                'path' => str_replace(['\\','//'], '/', $file),
            ];
            $data['shortpath'] = str_replace($root_abspath, '', $data['path']);

            $files['files'][$data['path']] = $data;
            $files['total_size'] += $data['size'];
            ++$files['count'];
        }

        usort($files['files'], function($a, $b) {
            return strnatcmp($a['path'], $b['path']);
        });


        $this->data->files = $files;
    }

    /**
     * Render tab title
     *
     * @return string
     */
    public function renderTitle()
    {
        return $this->data->files['count'].' files';
    }
}
