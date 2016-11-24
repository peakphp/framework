<?php
/**
 * Generate select form element
 * 
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_View_Helper_form_select extends Peak_View_Helper_tagattrs
{
    /**
     * Options data
     * @var array
     */
    private $_data = array();
    
    /**
     * Current selected element
     * @var string
     */
    private $_current;
    
    /**
     * Set/Overwrite options array
     *
     * @param  array $data
     * @return object
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }
    
    /**
     * Set/Add options array
     *
     * @param  array $data
     * @return object
     */
    public function addData(array $data)
    {
        foreach($data as $k => $v) {
            $this->_data[$k] = $v;
        }
        return $this;
    }
    
    /**
     * Set/Add an option key/val data
     *
     * @param  string $k
     * @param  string $v
     * @return object
     */
    public function addValue($k, $v)
    {
        $this->_data[$k] = $v;
        return $this;
    }

    /**
     * Set the selected option key
     *
     * @param  string $current
     * @return object
     */
    public function setSelected($current)
    {
        $this->_current = $current;
        return $this;
    }
    
    /**
     * Set select size
     *
     * @param  integer $current
     * @return object
     */
    public function setSize($size)
    {
        $this->_attrs['size'] = $size;
        return $this;
    }
    
    /**
     * Set/Remove 'disabled' attribute
     *
     * @param  bool $bool
     * @return object
     */
    public function disabled($bool)
    {
        if($bool === true) $this->_attrs['disabled'] = 'disabled';
        else unset($this->_attrs['disabled']);
        return $this;
    }
    
    /**
     * Set/Remove 'multiple' attribute
     *
     * @param  bool $bool
     * @return object
     */
    public function multiple($bool)
    {
        if($bool === true) $this->_attrs['multiple'] = 'multiple';
        else unset($this->_attrs['multiple']);
        return $this;
    }
    
    /**
     * Get Select element result
     *
     * @return string
     */
    public function get()
    {
        $element = '<select '.$this.'>';
        foreach($this->_data as $k => $v) {
            $element .= '<option value="'.$k.'"';
            if((string)$this->_current === (string)$k) $element .= ' selected';
            $element .= '>'.$v.'</option>';
        }
        $element .= '</select>';
        return $element; 
    }
    
    /**
     * Echo Select element result
     *
     * @param string $before
     * @param string $after
     */
    public function output($before = null, $after = null)
    {
        echo $before.$this->get().$after;
    }
    
    /**
     * Reset all aspect of Select element
     *
     * @return object
     */
    public function reset()
    {
        $this->_data = array();
        $this->_attrs = array();
        $this->_current = null;
        return $this;
    }
}