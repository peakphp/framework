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
    private $_data = [];

    /**
     * The columns
     * @var array
     */
    private $_columns = [];

    /**
     * The columns url pattern with binds
     * @var array
     */
    private $_columns_url = ['url' => '', 'binds' => '', 'result' => ''];

    /**
     * Set which column defines the table sorting
     * @var array
     */
    private $_column_sorting = ['name' => '', 'direction' => ''];

    /**
     * Excluded columns
     * @var array
     */
    private $_exclude_colums = [];

    /**
     * Hook for the data
     * @var array
     */
    private $_hooks = [];

    /**
     * Break line for html output
     * @var string
     */
    private $_line_break = "\n";

    /**
     * Table classes
     * @var string
     */
    private $_table_classes = 'table table-striped';

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
        $this->_data = $data;
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
        $this->_columns = [];
        foreach ($cols as $k => $v) {
            $this->_columns[trim($k)] = $v;
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
        $this->_columns_url = ['url' => $url, 'binds' => $binds, 'result' => $result];

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
        $this->_column_sorting = ['name' => $colname, 'direction' => $direction];
        return $this;
    }

    /**
     * Add columns to exclude columns
     * Useful with _discoverColumns()
     *
     * @param  array $cols
     * @return $this
     */
    public function excludeCols($cols)
    {
        if (is_array($cols)) {
            $this->_exclude_colums = [];
            if (!empty($cols)) {
                foreach ($cols as $c) {
                    $this->_exclude_colums[$c] = $c;
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

        $this->_hooks[$colname] = $h;

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
        $this->_line_break = $ln;
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

    public function addAttrsRowColumn()
    {
    }
    
    /**
     * Set table html class(es)
     *
     * @param  string $classes
     * @return $this
     */
    public function setTableClasses($classes)
    {
        $this->_table_classes = $classes;
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
            $this->_table_classes .= (substr($classes, 0, 1) === ' ') ? $classes : ' '.$classes;
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
    private function _isValidColumn($title, $row_sample = null)
    {
        //we will try to get a sample
        if (is_null($row_sample)) {
            if (!empty($this->_data)) {
                foreach ($this->_data as $index => $row) {
                    $row_sample = $row;
                    break;
                }
            } else {
                //cant get a sample
                return false;
            }
        }

        //ok, sample is there, we can check
        if (array_key_exists(trim($title), $row_sample) === true) {
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
    private function _isPseudoColumn($title)
    {
        if (substr($title, 0, 1) === ':' && isset($this->_hooks[$title])) {
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
        if (empty($this->_data) && !$force_output_on_empty_data) {
            return;
        }

        // discover columns if they where not specified
        if (empty($this->_columns)) {
            $this->_discoverColumns();
        }
        
        echo '<table class="'.$this->_table_classes.'">'.$this->_line_break;
        
        // head
        echo '<thead><tr>';
        foreach ($this->_columns as $colname => $coltitle) {

            if (($this->_isValidColumn($colname) || $force_output_on_empty_data) || $this->_isPseudoColumn($colname)) {

                echo '<th data-column="'.$colname.'"';

                // add data attr for sorting
                if ($this->_column_sorting['name'] === $colname) {
                    echo ' data-sorted="yes"';
                    if (!empty($this->_column_sorting['direction'])) {
                        echo ' data-direction="'.$this->_column_sorting['direction'].'"';
                    }
                }

                echo '>';

                // add url to column th
                if (!empty($this->_columns_url['result']) && !$this->_isPseudoColumn($colname)) {
                    $url = $this->_columns_url['result'];
                    $url = str_replace(':column', $colname, $url);

                    echo '<a href="'.$url.'">'.$coltitle.'</a>';
                } else {
                    echo $coltitle;
                }

                echo '</th>';
            }
        }
        echo '</tr></thead><tbody>'.$this->_line_break;
        
        //body
        if (!empty($this->_data)) {
            foreach ($this->_data as $index => $row) {

                // try to explicitly convert object to array
                if (is_object($row)) {
                    $row = (array)$row;
                }

                //new row
                echo '<tr data-index="'.$index.'"'.$this->_rowDataAttr($row).'>';
                
                foreach ($this->_columns as $colname => $coltitle) {

                    //check if column exists
                    if (!$this->_isValidColumn($colname, $row)) {
                        //here we check if its a valid "pseudo" column
                        if ($this->_isPseudoColumn($colname)) {
                            $row_data = $row;
                        } else {
                            continue;
                        }
                    } else {
                        $row_data = $row[$colname];
                    }

                    $row_data = $this->_processHook($colname, $row_data, $row);

                    //print the data
                    echo '<td data-column="'.$colname.'">'.$row_data.'</td>'.$this->_line_break;
                }
                //close row
                echo '</tr>'.$this->_line_break;
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
    private function _processHook($colname, $row_data, $row_context)
    {
        //look for hook baby!
        if (!isset($this->_hooks[$colname])) {
            return $row_data;
        }

        $hooks_col = $this->_hooks[$colname];

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

                // closure params stuff
                // if (is_array($params) && array_key_exists('fields', $params)) {

                //     if (is_array($params['fields'])) {
                //         $fields = [];
                //         foreach ($params['fields'] as $field) {
                //             if (array_key_exists($field, $row_data)) {
                //                 $fields[$field] = $row_data[$field];
                //             }
                //         }
                //         $params['fields'] = $fields;
                //     } elseif ($params['fields'] === '*') {
                //         $params['fields'] = $row_context;
                //     } elseif (array_key_exists($params['fields'], $row_data)) {
                //         $params['fields'] = $row_data[$h['fields']];
                //     } else {
                //         $params['fields'] = null;
                //     }
                // }

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
    private function _discoverColumns()
    {
        $cols = [];
        
        if (empty($this->_data)) {
            return;
        }
        
        foreach ($this->_data as $row) {
            if (is_array($row)) {
                foreach ($row as $colname => $value) {
                    if (!isset($this->_exclude_colums[$colname])) {
                        $cols[$colname] = $colname;
                    }
                }
            }
            break;
        }
        $this->_columns = $cols;
    }

    /**
     * Render html attribute(s) to each row(tr)
     */
    private function _rowDataAttr($row = [])
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
