<?php
namespace Peak\View\Form;

use Peak\Collection;

class FormBuilder extends Collection
{
    protected $form;

    /**
     * Add App\Views\Helpers\Form
     */
    public function __construct($form = null)
    {
        $this->form = $form;
        $this->init();
    }

    /**
     * Custom stuff to do on launch
     */
    public function init() {}

    /**
     * Get a html control (use App\Views\Helpers\FormControl)
     * 
     * @param  object|string $arg1 
     * @param  string $arg1 
     * @return object       
     */
    public function control($arg1, $arg2 = null)
    {
        if(is_string($arg1) && !isset($arg2)) {
            $name = $arg1;
            $form = $this->form;
        }  
        else {
            $form = $arg1;
            $name = $arg2;
        }

        if(!isset($this->items[$name])) {
            trigger_error('Field '.$name.' not found');
            return null;
        }

        // call preload(if specified) only when we need the form control
        if(array_key_exists('preload', $this->items[$name])) {
            $method = $this->items[$name]['preload'];
            $this->$method($this->items[$name]);
        }

        return 
            
            $form->control(
                $this->items[$name]['type'],
                $name,
                $this->items[$name]['settings']
            );
    }

    /**
     * Add form buttons
     * 
     * @param  string $cancel_url url base used for cancel button
     * @param  string $mode       edit or new
     */
    public function buttons($cancel_url, $mode = 'new')
    {
        $html = '';
        $v    = \Peak\Registry::o()->view;

        $html .= '<a class="btn btn-default" href="'.$v->baseUrl($cancel_url, true).'">'.__('Cancel').'</a>';

        $submit_button_text = ($mode === 'edit') ? __('Update') : __('Save');
        
        $html .= '<button class="btn btn-primary">'.$submit_button_text.'</button>';

        echo $html;
    }
}