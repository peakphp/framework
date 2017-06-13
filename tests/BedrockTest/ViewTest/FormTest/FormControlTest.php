<?php
use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Form\Control\Input;
use Peak\Bedrock\View\Form\Control\Checkbox;
use Peak\Bedrock\View\Form\Control\Select;
use Peak\Bedrock\View\Form\Control\Textarea;

class FormControlTest extends TestCase
{

    /**
     * Test input form control
     */
    function testInput()
    {
        $name = 'myinput';
        $data = 'test';
        $options = [
            'attrs' => [
                'class' => 'form-control'
            ]
        ];
        $error = null;

        $el = new Input($name, $data, $options, $error);
        $el_html = $el->generate();
        $el_html2 = $el->get();

        $result = '<input id="field-myinput" name="myinput" value="test" class="form-control" placeholder="" spellcheck="true" type="text" ref="myinput">';

        $this->assertTrue($el_html === $result);
        $this->assertTrue($el_html2 === $result);

        ob_start();
        $el->render();
        $content = ob_get_clean();
        $this->assertTrue($content === $result);
    }

    /**
     * Test checkbox form control
     */
    function testCheckbox()
    {
        $name = 'myelement';
        $data = 'test';
        $options = [
            'attrs' => [
                'class' => 'form-control'
            ]
        ];
        $error = null;

        $el = new Checkbox($name, $data, $options, $error);
        $el_html = $el->generate();
        $el_html2 = $el->get();

        //echo $el_html;

        $result = '<input id="field-myelement" name="myelement" value="test" class="form-control" type="checkbox">';

        $this->assertTrue($el_html === $result);
        $this->assertTrue($el_html2 === $result);

        ob_start();
        $el->render();
        $content = ob_get_clean();
        $this->assertTrue($content === $result);
    }

    /**
     * Test textarea form control
     */
    function testTextarea()
    {
        $name = 'myelement';
        $data = 'test';
        $options = [
            'attrs' => [
                'class' => 'form-control'
            ]
        ];
        $error = null;

        $el = new Textarea($name, $data, $options, $error);
        $el_html = $el->generate();
        $el_html2 = $el->get();

        //echo $el_html;

        $result = '<textarea id="field-myelement" name="myelement" class="form-control" placeholder="" row="3">test</textarea>';

        $this->assertTrue($el_html === $result);
        $this->assertTrue($el_html2 === $result);

        ob_start();
        $el->render();
        $content = ob_get_clean();
        $this->assertTrue($content === $result);
    }

    /**
     * Test select form control
     */
    function testSelect()
    {
        $name = 'myelement';
        $data = 'test';
        $options = [
            'options' => [
                'a' => 'option a',
                'b' => 'option b'
            ],
            'attrs' => [
                'class' => 'form-control'
            ]
        ];
        $error = null;

        $el = new Select($name, $data, $options, $error);
        $el_html = $el->generate();
        $el_html2 = $el->get();

        $result = '<select id="field-myelement" name="myelement" class="form-control" placeholder=""><option  value="a">option a</option><option  value="b">option b</option></select>';

        $this->assertTrue($el_html === $result);
        $this->assertTrue($el_html2 === $result);

        ob_start();
        $el->render();
        $content = ob_get_clean();
        $this->assertTrue($content === $result);
    }

    /**
     * Test select form control
     */
    function testSelect2()
    {
        $name = 'myelement';
        $data = 'test';
        $options = [
            'options' => [
                'a' => 'option a',
                'b' => 'option b'
            ],
            'value_as_key' => true,
            'attrs' => [
                'class' => 'form-control'
            ]
        ];
        $error = null;

        $el = new Select($name, $data, $options, $error);
        $el_html = $el->generate();

        $result = '<select id="field-myelement" name="myelement" class="form-control" placeholder=""><option  value="option a">option a</option><option  value="option b">option b</option></select>';

        $this->assertTrue($el_html === $result);
    }
}