<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\FormBuilder;
use Peak\Bedrock\View\Form\FormValidation;

include __DIR__.'/../../fixtures/view/form/FormBuilderExample.php';

class FormBuilderTest extends TestCase
{
     
    /**
     * Validate a form builder
     */
    function testValidateForm()
    {
        $form_validation = new FormValidation(new FormBuilderExample());

        $pass = $form_validation->validate([
            'id' => '',
            'user' => 'bob',
        ]);

        $this->assertTrue($pass);
    }

    /**
     * Validate a form builder directly with FormBuilder
     */
    function testValidateFormQuick()
    {
        $form = new FormBuilderExample();

        $pass = $form->validate([
            'id' => '',
            'user' => 'bob',
        ]);

        $this->assertTrue($pass);
    }

   

}