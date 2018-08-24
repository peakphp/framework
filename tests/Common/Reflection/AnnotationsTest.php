<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Reflection\Annotations;

class AnnotationsTest extends TestCase
{
    /**
     * Test getClass
     */
    function testGetClass()
    {
        $an = new Annotations(MyClass::class);
        $tags = $an->getClass();
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 2);
        $this->assertTrue($tags[0]['tag'] == 'author');

        $tags = $an->getClass('unknow_tags');
        $this->assertTrue(empty($tags));
    }

    /**
     * Test getClass from an instance
     */
    function testGetClassWithAnInstance()
    {
        $myclass = new MyClass();
        $an = new Annotations($myclass);
        $tags = $an->getClass();
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 2);
        $this->assertTrue($tags[0]['tag'] == 'author');

        $tags = $an->getClass('unknow_tags');
        $this->assertTrue(empty($tags));
    }

    /**
     * Test getClass() with specific tag
     */
    function testGetClass2()
    {
        $an = new Annotations(MyClass::class);
        $tags = $an->getClass('copyright');
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 1);
        $this->assertTrue($tags[0]['tag'] == 'copyright');
    }

    /**
     * Test getMethod
     */
    function testGetMethod()
    {
        $an = new Annotations(MyClass::class);
        $tags = $an->getMethod('__construct');
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 2);
        $this->assertTrue($tags[0]['tag'] == 'random');
        $this->assertTrue($tags[1]['tag'] == 'param');

        $tags = $an->getMethod('__construct', ['random', 'param', 'unknown']);
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 2);
        $this->assertTrue($tags[0]['tag'] == 'random');
        $this->assertTrue($tags[1]['tag'] == 'param');

        $tags = $an->getMethod('fooBar');
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 4);
        $this->assertTrue($tags[0]['tag'] == 'var');
        $this->assertTrue($tags[1]['tag'] == 'var');
        $this->assertTrue($tags[2]['tag'] == 'var');
        $this->assertTrue($tags[3]['tag'] == 'var');

        $this->assertTrue(count($tags[2]['data']) == 2);
        $this->assertTrue(count($tags[3]['data']) == 1);


        $tags = $an->getMethod('unknow_tags');
        $this->assertTrue(empty($tags));
    }

    /**
     * Test getMethod with specific tag
     */
    function testGetMethod2()
    {
        $an = new Annotations(MyClass::class);
        $tags = $an->getMethod('__construct', 'random');
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 1);
        $this->assertTrue($tags[0]['tag'] == 'random');
    }

    /**
     * Test getAllMethods with specific tag
     */
    function testGetAllMethods()
    {
        $an = new Annotations(MyClass::class);
        $tags = $an->getAllMethods('random');
        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 2);
        $this->assertTrue(isset($tags['__construct']));
        $this->assertTrue(count($tags['__construct']) == 1);
    }

    /**
     * Test static parse()
     */
    function testStaticParse()
    {
        $docblock = '
            /**
             * FooBar
             * @myanno value1 value2 value3
             */
        ';

        $tags = Annotations::parse($docblock);

        $this->assertTrue(is_array($tags));
        $this->assertTrue(count($tags) == 1);
        $this->assertTrue($tags[0]['tag'] == 'myanno');
        $this->assertTrue(count($tags[0]['data']) == 3);

        // empty docblock
        $docblock = '';
        $tags = Annotations::parse($docblock);
        $this->assertTrue(empty($tags));
    }
}

/**
 * This is an example class
 *
 * @author Mr. FooBar
 * @copyright 2017
 */
class MyClass
{
    /**
     * Arguments
     * @var array
     */
    public $args;
    /**
     * Constructor
     *
     * @random value1
     * @param array $args
     */
    public function __construct($args = [])
    {
        $this->args = $args;
    }

    /**
     * FooBar
     * @var 1
     * @var 2
     * @var 2 param
     * @var "one param"
     */
    public function fooBar()
    {
    }
}