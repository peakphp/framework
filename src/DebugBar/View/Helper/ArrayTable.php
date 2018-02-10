<?php

namespace Peak\DebugBar\View\Helper;

use Peak\Common\Interfaces\Renderable;

class ArrayTable implements Renderable
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * ArrayTable constructor.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Render the table
     *
     * @return string
     */
    public function render()
    {
        if (!is_array($this->data)) {
            return 'No data';
        }

        $content = '<table class="border-inside">';

        foreach ($this->data as $key => $val) {
            $content .= '<tr>';
            if (is_array($val)) {
                foreach ($val as $item) {
                    $content .= '<td>'.$item.'</td>';
                }
            } else {
                $content .= '<td>'.$key.'</td><td>'.$val.'</td>';
            }

            $content .= '</tr>';
        }

        $content .= '</table>';

        return $content;
    }
}
