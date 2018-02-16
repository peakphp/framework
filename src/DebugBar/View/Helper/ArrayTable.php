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

        $content = '<table class="table-full-width table-border-inside">';

        foreach ($this->data as $key => $val) {
            $content .= '<tr>';
            if (is_array($val)) {
                $content .= '<td colspan="2">'.$key.'</td></tr>';
                foreach ($val as $vkey => $item) {
                    $content .= '<tr><td>&nbsp;&nbsp;└&nbsp;'.$vkey.'</td><td>'.$this->formatVal($item).'</td></tr>';
                }
            } else {
                $content .= '<td>'.$key.'</td><td>'.$this->formatVal($val).'</td>';
            }

            $content .= '</tr>';
        }

        $content .= '</table>';

        return $content;
    }

    /**
     * Format value
     *
     * @param $val
     * @return string
     */
    public function formatVal($val)
    {
        if (is_bool($val)) {
            $val = ($val === true) ? 'true' : 'false';
        }
        return $val;
    }
}
