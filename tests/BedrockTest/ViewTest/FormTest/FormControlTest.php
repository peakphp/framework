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
        $el_html2 = $el->get();

        $result = '<input id="field-myinput" name="myinput" value="test" class="" placeholder="" spellcheck="true" type="text" ref="myinput">';

        $this->assertTrue($el_html === $result);
        $this->assertTrue($el_html2 === $result);

        ob_start();
        $el->render();
        $content = ob_get_clean();
        $this->assertTrue($content === $result);
    }

}