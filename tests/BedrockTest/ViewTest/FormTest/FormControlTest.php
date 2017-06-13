<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\Control\Input;

class FormControlTest extends TestCase
{

    /**
     * Test input form control
     */
    function testInput()
    {
        $name = 'myinput';
        $data = 'test';
        $options = [];
        $error = null;

        $el = new Input($name, $data);
        $el_html = $el->generate();

        $this->assertTrue($el_html === '<input id="field-myinput" name="myinput" value="test" class="" placeholder="" spellcheck="true" type="text" ref="myinput">');
    }

}