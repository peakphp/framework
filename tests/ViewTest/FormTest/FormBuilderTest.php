<?php
use PHPUnit\Framework\TestCase;

use Peak\View\Form\FormBuilder;
use Peak\View\Form\FormValidation;

include __DIR__.'/../../fixtures/view/form/FormBuilderExample.php';

class FormBuilderTest extends TestCase
{
     
    /**
     * Create object
     */
    function testCreateObject()
    {
        $form_validation = new FormValidation(new FormBuilderExample());

        $pass = $form_validation->validate([
            'id' => '',
            'user' => 'bob',
        ]);

        $this->assertTrue($pass);
    }

   

}