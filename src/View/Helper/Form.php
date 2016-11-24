<?php
/**
 * Access to /form/ objects helpers
 *
 * @author  Francois Lajoie
 * @version $Id: form.php 494 2012-02-25 04:28:35Z snake386@hotmail.com $
 */
class Peak_View_Helper_Form
{
    /**
     * Select form element 
     *
     * @return Peak_View_Helper_Form_Select
     */
    public function select()
    {
        return new Peak_View_Helper_form_select();
    }
    
    /**
     * Input form element
     *
     * @return Peak_View_Helper_Form_Input
     */
    public function input()
    {
        return new Peak_View_Helper_form_input();
    }
}