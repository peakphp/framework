<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\Form;
use Peak\Bedrock\View\Form\FormControl;
use Peak\Bedrock\View\Form\Control\Input;

class FormTest extends TestCase
{
    /**
     * test application container
     */
    function testCreateAndGet()
    {
        $form = new Form(
            ['foo' => 'bar'], 
            ['bar' => 'foo is not bar']
        );

        $this->assertTrue($form->get('foo') === 'bar');
        $this->assertTrue($form->getError('bar') === 'foo is not bar');
        $this->assertTrue($form->get('foo2') === null);
    }


    /**
     * test application container
     */
    function testSet()
    {
        $form = new Form(
            ['foo' => 'bar'], 
            ['bar' => 'foo is not bar']
        );

        $form->setData(['foo' => 'bar2']);
        $form->setErrors(['bar' => 'foo']);

        $this->assertTrue($form->get('foo') === 'bar2');
        $this->assertTrue($form->getError('bar') === 'foo');
        $this->assertTrue($form->get('foo2') === null);
    }

    /**
     * Test control
     */
    function testFormControl()
    {
        $form = new Form(
            ['foo' => 'bar'], 
            ['bar' => 'foo is not bar']
        );

        $control = $form->control('input', 'myname');

        $this->assertTrue($control instanceof FormControl);
        $this->assertTrue($control instanceof Input);
    }


}