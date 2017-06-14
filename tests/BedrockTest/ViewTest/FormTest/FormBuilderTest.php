<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\Form;
use Peak\Bedrock\View\Form\FormBuilder;
use Peak\Bedrock\View\Form\FormValidation;
use Peak\Bedrock\View\Form\Control\Input;

include FIXTURES_PATH.'/view/form/FormBuilderExample.php';

class FormBuilderTest extends TestCase
{
     
    /**
     * Validate a form builder
     */
    function testValidateForm()
    {
        $form_validation = new FormValidation(new FormBuilderExample(new Form()));

        $pass = $form_validation->validate([
            'id' => '',
            'user' => 'bob',
        ]);

        $this->assertTrue($pass);
    }

    /**
     * Validate a form builder directly with FormBuilder
     */
    function testValidate2()
    {
        $form = new FormBuilderExample(new Form([
            'id' => '',
            'user' => 'bob',
        ]));

        $pass = $form->validate();

        $this->assertTrue($pass);
    }

    /**
     * Validate a form builder directly with FormBuilder
     */
    function testValidateFormQuick()
    {
        $form = new FormBuilderExample(new Form());

        $pass = $form->validate([
            'id' => '',
            'user' => 'bob',
        ]);

        $this->assertTrue($pass);
    }

    /**
     * Test form control
     */
    function testFormControl()
    {
        $form1 = new Form();
        $form2 = new Form(['id' => 10]);
        $formBuilder = new FormBuilderExample($form1);

        $control = $formBuilder->control('id');
        $this->assertTrue($control instanceof Input);
        $this->assertTrue($control->generate() === '<input id="field-id" name="id" class="" placeholder="" spellcheck="true" type="hidden" test="123123" ref="id">');

        $control = $formBuilder->control($form2,'id');
        $this->assertTrue($control instanceof Input);
        $this->assertTrue($control->generate() === '<input id="field-id" name="id" value="10" class="" placeholder="" spellcheck="true" type="hidden" test="123123" ref="id">');

        //exception (first param should be a Form instance
        try {
            $control = $formBuilder->control('id', $form2);
        } catch (Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));

        // should trigger error on unknown field name
        try {
            $control = $formBuilder->control('id2');
        } catch (Exception $e) {
            $error2 = true;
        }

        $this->assertTrue(isset($error2));
    }

    function testSetData()
    {
        $form = new Form();
        $formBuilder = new FormBuilderExample($form);
        $this->assertTrue($form->get('id') === null);
        $formBuilder->setData(['id' => 'test']);
        $this->assertTrue($form->get('id') === 'test');
    }

    function testSetDataException()
    {
        $formBuilder = new FormBuilderExample();
        try {
            $formBuilder->setData(['id' => 'test']);
        } catch (Exception $e) {
            $error = true;
        }
        $this->assertTrue(isset($error));
    }

    function testSetErrors()
    {
        $form = new Form();
        $formBuilder = new FormBuilderExample($form);
        $this->assertTrue($form->get('id') === null);
        $formBuilder->setErrors(['id' => 'test']);
        $this->assertTrue($form->getError('id') === 'test');
    }

    function testSetErrorsException()
    {
        $formBuilder = new FormBuilderExample();
        try {
            $formBuilder->setError(['id' => 'test']);
        } catch (Exception $e) {
            $error = true;
        }
        $this->assertTrue(isset($error));
    }
}