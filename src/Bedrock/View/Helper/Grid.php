<?php

namespace Peak\Bedrock\View\Helper;

use Peak\Bedrock\View\Helper;

/**
 * Table Grid Helper
 *
 * Create table easily
 */
class Grid extends Helper
{
    /**
     * The grid data
     * @var array
     */
    private $data = [];

    /**
     * The columns
     * @var array
     */
    private $columns = [];

    /**
     * The columns url pattern with binds
     * @var array
     */
    private $columns_url = ['url' => '', 'binds' => '', 'result' => ''];

    /**
     * Set which column defines the table sorting
     * @var array
     */
    private $column_sorting = ['name' => '', 'direction' => ''];

    /**
     * Excluded columns
     * @var array
     */
    private $exclude_colums = [];

    /**
     * Hook for the data
     * @var array
     */
    private $hooks = [];

    /**
     * Break line for html output
     * @var string
     */
    private $line_break = "\n";

    /**
     * Table classes
     * @var string
     */
    private $table_classes = 'table table-striped';

    /**
     * Set-up the grid data
     *
     * @param array|null $data
     */
    public function __construct($data = null)
    {
        if (is_array($data)) {
            $this->setData($data);
        }
    }

    /**
     * Set/Overwrite data
     *
     * @param  array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set column name and title
     *
     * ex: array('uname' => "Username", 'id' => 'Unique Id', ...)
     *
     * pseudo additional column must begin by ":"
     * ex: array(':newcol' => 'Extra column', ...)
     *
     * @param  array $cols
     * @return $this
     */
    public function setColumns($cols)
    {
        $this->columns = [];
        foreach ($cols as $k => $v) {
            $this->columns[trim($k)] = $v;
        }
        return $this;
    }

    /**
     * Set an url pattern for columns head th with their binds if any
     *
     * @param  string      $url
     * @param  array|null  $binds
     * @return $this
     */
    public function setColumnsUrl($url, $binds = null)
    {
        $result = $url;
        if (!empty($binds) && is_array($binds)) {
            foreach ($binds as $k => $v) {
                $result = str_replace(':'.$k, $v, $result);
            }
        }
        $this->columns_url = ['url' => $url, 'binds' => $binds, 'result' => $result];

        return $this;
    }

    /**
     * Set column sorting
     *
     * @param  string $colname
     * @param  string $direction
     * @return $this
     */
    public function setColumnSorting($colname, $direction = 'desc')
    {
        $this->column_sorting = ['name' => $colname, 'direction' => $direction];
        return $this;
    }

    /**
     * Add columns to exclude columns
     * Useful with discoverColumns()
     *
     * @param  array $cols
     * @return $this
     */
    public function excludeCols($cols)
    {
        if (is_array($cols)) {
            $this->exclude_colums = [];
            if (!empty($cols)) {
                foreach ($cols as $c) {
                    $this->exclude_colums[$c] = $c;
                }
            }
        }
        return $this;
    }

    /**
     * Add hook data for a column name
     *
     * THERE IS 3 WAYS TO USE THIS METHOD:
     *
     *  #1 ->addHook('email', [
    [$hook_strong, ['param1' => 'foo']],
    $hook_link,
    $hook_strong
    ])
     *
     *  #2 ->addHook('email', $hook_edit)
     *
     *  #3 ->addHook('email', $hook_strong)
     *
     * @param string      $colname
     * @param string      $fn
     * @param array|null  $params  params for $fn
     */
    public function addHook($colname, $fn, $params = null)
    {
        if (is_array($fn)) {
            $h = ['hooks' => $fn];
        } else {
            if (!is_null($params)) {
                $h = [
                    'hooks' => [
                        [$fn, $params]
                    ]
                ];
            } else {
                $h = [
                    'hooks' => [
                        [$fn]
                    ]
                ];
            }
        }

        $this->hooks[$colname] = $h;

        return $this;
    }

    /**
     * Set render html linebreak
     *
     * @param  string $ln
     * @return $this
     */
    public function setRenderLineBreak($ln)
    {
        $this->line_break = $ln;
        return $this;
    }

    /**
     * Fill html attribute to each row(tr) with a row column or customize value
     *
     * @param  string     $attr
     * @param  string     $column
     * @param  array|null $value
     * @return $this
     */
    public function addRowDataAttr($attr, $column, $value = null)
    {
        $this->_row_data_attr = '';
        if (is_null($value)) {
            $this->_row_data_attrs[$attr] = $column;
        } else {
            $this->_row_data_attrs[$attr] = ['value' => $value];
        }

        return $this;
    }

    /**
     * Set table html class(es)
     *
     * @param  string $classes
     * @return $this
     */
    public function setTableClasses($classes)
    {
        $this->table_classes = $classes;
        return $this;
    }

    /**
     * Set table html class(es)
     *
     * @param  string $classes
     * @return $this
     */
    public function addTableClasses($classes)
    {
        if (!empty($classes)) {
            $this->table_classes .= (substr($classes, 0, 1) === ' ') ? $classes : ' '.$classes;
        }
        return $this;
    }

    /**
     * Check if a column name exists in the actual data set
     *
     * @param  string     $title
     * @param  array|null $row_sample
     * @return bool
     */
    private function isValidColumn($title, $row_sample = null)
    {
        //we will try to get a sample
        if (is_null($row_sample)) {
            if (!empty($this->data)) {
                foreach ($this->data as $row) {
                    $row_sample = $row;
                    break;
                }
            } else {
                //cant get a sample
                return false;
            }
        }

        if(is_object($row_sample)) {
            $row_sample = $this->convertObjectToArray($row_sample);
        }

        //ok, sample is there, we can check
        if (is_array($row_sample) && array_key_exists(trim($title), $row_sample) === true) {
            return true;
        }
        return false;
    }

    /**
     * here we check if its a "pseudo" column
     * note: a pseudo column MUST HAVE a hook
     *
     * @param  string $title
     * @return bool
     */
    private function isPseudoColumn($title)
    {
        if (substr($title, 0, 1) === ':' && isset($this->hooks[$title])) {
            return true;
        }
        return false;
    }

    /**
     * Render the grid
     *
     * @return string
     */
    public function render($force_output_on_empty_data = false)
    {
        // skip everything if we don't have data
        if (empty($this->data) && !$force_output_on_empty_data) {
            return;
        }


        // discover columns if they where not specified
        if (empty($this->columns)) {
            $this->discoverColumns();
        }

        echo '<table class="'.$this->table_classes.'">'.$this->line_break;

        // head
        echo '<thead><tr>';
        foreach ($this->columns as $colname => $coltitle) {
            if (($this->isValidColumn($colname) || $force_output_on_empty_data) || $this->isPseudoColumn($colname)) {

                echo '<th data-column="'.$colname.'"';

                // add data attr for sorting
                if ($this->column_sorting['name'] === $colname) {
                    echo ' data-sorted="yes"';
                    if (!empty($this->column_sorting['direction'])) {
                        echo ' data-direction="'.$this->column_sorting['direction'].'"';
                    }
                }

                echo '>';

                // add url to column th
                if (!empty($this->columns_url['result']) && !$this->isPseudoColumn($colname)) {
                    $url = $this->columns_url['result'];
                    $url = str_replace(':column', $colname, $url);

                    echo '<a href="'.$url.'">'.$coltitle.'</a>';
                } else {
                    echo $coltitle;
                }

                echo '</th>';
            } else {
                print_r("ET");
            }
        }
        echo '</tr></thead><tbody>'.$this->line_break;

        //body
        if (!empty($this->data)) {
            foreach ($this->data as $index => $row) {
                // try to explicitly convert object to array
                if (is_object($row)) {
                    $row = $this->convertObjectToArray($row);
                }

                //new row
                echo '<tr data-index="'.$index.'"'.$this->rowDataAttr($row).'>';

                foreach ($this->columns as $colname => $coltitle) {
                    //check if column exists
                    if (!$this->isValidColumn($colname, $row)) {
                        //here we check if its a valid "pseudo" column
                        if ($this->isPseudoColumn($colname)) {
                            $row_data = $row;
                        } else {
                            continue;
                        }
                    } else {
                        $row_data = $row[$colname];
                    }

                    $row_data = $this->processHook($colname, $row_data, $row);

                    //print the data
                    echo '<td data-column="'.$colname.'">'.$row_data.'</td>'.$this->line_break;
                }
                //close row
                echo '</tr>'.$this->line_break;
            }
        }

        echo '</tbody></table>';
    }

    /**
     * Process a hook on a data
     *
     * @param  string $colname     The column name to hook
     * @param  string $row_data    The data to hook
     * @param  array  $row_context The other array data rows
     * @return string
     */
    private function processHook($colname, $row_data, $row_context)
    {
        //look for hook baby!
        if (!isset($this->hooks[$colname])) {
            return $row_data;
        }

        $hooks_col = $this->hooks[$colname];

        //array of hook
        if (is_array($hooks_col)) {
            $hooks = $hooks_col['hooks'];
            foreach ($hooks as $h) {
                // reset
                $hook_fn = null;
                $params  = null;

                // check if we have multiples hooks with/without params
                if (is_array($h)) {
                    if (array_key_exists(0, $h)) {
                        $hook_fn = $h[0];
                    }
                    if (array_key_exists(1, $h)) {
                        $params = $h[1];
                    }
                } else {
                    // its just a plain hook with no params
                    $hook_fn = $h;
                    $params = null;
                }

                if (!is_array($params)) {
                    $params = ['data' => $row_context];
                } else {
                    $params['data'] = $row_context;
                }

                // execute closure
                $row_data = $hook_fn($row_data, $params);
            }
        }

        return $row_data;
    }

    /**
     * Discover columns from $_data array
     * Useful when you don't use method setColumns()
     */
    private function discoverColumns()
    {
        $cols = [];

        foreach ($this->data as $row) {
            if (is_object($row)) {
                $row = $this->convertObjectToArray($row);
            }

            if (is_array($row)) {
                foreach ($row as $colname => $value) {
                    if (!isset($this->exclude_colums[$colname])) {
                        $cols[$colname] = $colname;
                    }
                }
            }
            break;
        }

        $this->columns = $cols;
    }

    /**
     * Convert object to array
     * @param $object
     * @return array
     */
    private function convertObjectToArray($object)
    {
        if (method_exists($object, 'toArray')) {
            return $object->toArray();
        }
        return (array)$object;
    }

    /**
     * Render html attribute(s) to each row(tr)
     */
    private function rowDataAttr($row = [])
    {
        $r = '';

        if (empty($this->_row_data_attrs)) {
            return $r;
        }

        foreach ($this->_row_data_attrs as $attr => $v) {
            if (is_array($v)) {
                if (array_key_exists('value', $v)) {
                    $r .= ' '.$attr.'="'.$v['value'].'"';
                }
            } else {
                if ((array_key_exists($v, $row))) {
                    $r .= ' '.$attr.'="'.$row[$v].'"';
                }
            }
        }

        return $r;
    }
}
