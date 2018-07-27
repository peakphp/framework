<?php
use PHPUnit\Framework\TestCase;

use Peak\Common\Collection\DotNotationCollection;

class DotNotationCollectionTest extends TestCase
{

    protected $_array_test_1 = [
        'foo' => [
            'bar' => 123,
            'deep' => [
                '\bar$' => 'ABC'
            ]
        ]
    ];

    function testCreateObject()
    {
        $dn = new DotNotationCollection(['foo' => 'bar']);
        $this->assertFalse($dn->isEmpty());
    }
          

    function testGetPath()
    {
        $dn = new DotNotationCollection($this->_array_test_1);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->get('foo.bar') == 123);
        $this->assertTrue($dn->get('foo.deep.\bar$') === 'ABC');
        $this->assertTrue($dn->get('foo.test') === null);
    }

    function testSetPath()
    {
        $dn = new DotNotationCollection($this->_array_test_1);

        $dn->set('foo.jade.profile.new', ['test' => ['of' => 'path']]);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->get('foo.bar') == 123);

        $jade = $dn->get('foo.jade.profile.new');
        $this->assertTrue(is_array($jade));
        $this->assertTrue($jade['test']['of'] === 'path');
    }

    function testHavePath()
    {
        $dn = new DotNotationCollection($this->_array_test_1);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->has('foo.bar'));
        $this->assertTrue($dn->has('foo.deep.\bar$'));
        $this->assertFalse($dn->has('bar.foo'));
    }

    function testAdd()
    {
        $dn = new DotNotationCollection($this->_array_test_1);

        $dn->add('foo.bar', ['test' => 456]);

        $this->assertTrue($dn->has('foo.bar.test'));
        $this->assertTrue($dn->has('foo.bar.0'));


        $dn->add('foo.bar', ['last' => 789]);
        $this->assertTrue($dn->has('foo.bar.test'));
        $this->assertTrue($dn->has('foo.bar.0'));
        $this->assertTrue($dn->has('foo.bar.last'));

        $this->assertTrue($dn->get('foo.bar.0') == 123);
        $this->assertTrue($dn->get('foo.bar.test') == 456);
        $this->assertTrue($dn->get('foo.bar.last') == 789);
    }
}