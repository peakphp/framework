<?php

namespace Peak\DebugBar\View\Helper;

use Peak\Blueprint\Common\Renderable;

class ArrayTable implements Renderable
{
    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var string
     */
    protected $table_class = 'table-full-width table-border-inside';

    /**
     * @var string
     */
    protected $first_column_class = '';

    /**
     * @var string
     */
    protected $last_column_class = '';

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
     * Set table class
     *
     * @param string $class
     * @return $this
     */
    public function setTableClass($class)
    {
        $this->table_class = $class;
        return $this;
    }

    /**
     * Set the first column td class
     *
     * @param string $class
     * @return $this
     */
    public function setFirstColumnClass($class)
    {
        $this->first_column_class = $class;
        return $this;
    }

    /**
     * Set the last column td class
     *
     * @param string $class
     * @return $this
     */
    public function setLastColumnClass($class)
    {
        $this->last_column_class = $class;
        return $this;
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

        $content = '<table class="'.$this->table_class.'">';

        foreach ($this->data as $key => $val) {
            $content .= '<tr>';
            if (is_array($val)) {
                $content .= '<td colspan="2">'.$key.'</td></tr>';
                foreach ($val as $vkey => $item) {
                    $vkey = '&nbsp;&nbsp;└&nbsp;'.$vkey;
                    $content .= '<tr>'.$this->renderTdRow($vkey, $item).'</tr>';
                }
            } else {
                $content .= $this->renderTdRow($key, $val);
            }
            $content .= '</tr>';
        }

        $content .= '</table>';

        return $content;
    }

    /**
     * Render a table row
     *
     * @param string $key
     * @param mixed $val
     * @return string
     */
    protected function renderTdRow($key, $val)
    {
        return '
            <td class="'.$this->first_column_class.'">'.$key.'</td>
            <td class="'.$this->last_column_class.'">'.$this->formatVal($val).'</td>';
    }

    /**
     * Format value
     *
     * @param mixed $val
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
