<?php
/**
 * Generate input form element
 *
 * @author  Francois Lajoie
 * @version $Id$
 */
class Peak_View_Helper_form_input extends Peak_View_Helper_tagattrs
{
    
    /**
     * Set input type
     *
     * @param  string $type
     * @return object
     */
    public function setType($type)
    {
        $this->_attrs['type'] = $type;
        return $this;
    }
    
    /**
     * Set input value
     *
     * @param  string $value
     * @return object
     */
    public function setValue($value)
    {
        $this->_attrs['value'] = $value;
        return $this;
    }
    
    /**
     * Set input 'maxlenght' attribut
     *
     * @param integer $length
     */
    public function setMaxlength($length)
    {
        $this->_attrs['maxlength'] = $length;
        return $this;
    }
    
    /**
     * Set/Remove 'readonly' attribute
     *
     * @param  bool $bool
     * @return object
     */
    public function readonly($bool)
    {
        if($bool === true) $this->_attrs['checked'] = 'checked';
        else unset($this->_attrs['checked']);
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
     * Set/Remove 'checked' attribute
     *
     * @param  bool $bool
     * @return object
     */
    public function checked($bool)
    {
        if($bool === true) $this->_attrs['checked'] = 'checked';
        else unset($this->_attrs['checked']);
        return $this;
    }
    
    /**
     * Get Input element result
     *
     * @return string
     */
    public function get()
    {
        return '<input '.$this.' />';
    }
    
    /**
     * Echo Input element result
     *
     * @param string $before
     * @param string $after
     */ 
    public function output($before = null, $after = null)
    {
        echo $before.$this->get().$after;
    }
    
    /**
     * Reset all aspect of Input element
     *
     * @return object
     */
    public function reset()
    {
        $this->_attrs = array();
        return $this;
    }
}