<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\Element\Label;
use Peak\Bedrock\View\Form\Element\Error;

class ElementTest extends TestCase
{

    /**
     * Test label element
     */
    function testLabel()
    {
        $name = 'mylabel';
        $data = null;
        $options = [];
        $error = null;

        $label = new Label($name, $data);
        $label_html = $label->generate();

        $this->assertTrue($label_html === '<label id="field-mylabel" name="mylabel"></label>');
    }

    /**
     * Test error element
     */
    function testError()
    {
        $el = new Error('myerror', null);
        $el_html = $el->generate();

        $this->assertTrue(empty($el_html));

        $el = new Error('myerror', 'Dang!');
        $el_html = $el->generate();
        $this->assertTrue($el_html === '<p id="field-myerror" name="myerror" class="error">Dang!</p>');
    }
}