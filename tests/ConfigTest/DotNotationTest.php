<?php
use PHPUnit\Framework\TestCase;

/**
 * @package    Peak\Config
 */
class DotNotationTest extends TestCase
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
        $dn = new \Peak\Config\DotNotation(['foo' => 'bar']);
        $this->assertFalse($dn->isEmpty());
    }
          

    function testGetPath()
    {
        $dn = new \Peak\Config\DotNotation($this->_array_test_1);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->get('foo.bar') == 123);
        $this->assertTrue($dn->get('foo.deep.\bar$') === 'ABC');
        $this->assertTrue($dn->get('foo.test') === null);
    }

    function testSetPath()
    {
        $dn = new \Peak\Config\DotNotation($this->_array_test_1);

        $dn->set('foo.jade.profile.new', ['test' => ['of' => 'path']]);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->get('foo.bar') == 123);

        $jade = $dn->get('foo.jade.profile.new');
        $this->assertTrue(is_array($jade));
        $this->assertTrue($jade['test']['of'] === 'path');
    }

    function testHavePath()
    {
        $dn = new \Peak\Config\DotNotation($this->_array_test_1);

        $this->assertFalse($dn->isEmpty());
        $this->assertTrue($dn->have('foo.bar'));
        $this->assertTrue($dn->have('foo.deep.\bar$'));
        $this->assertFalse($dn->have('bar.foo'));
    }

    function testAdd()
    {
        $dn = new \Peak\Config\DotNotation($this->_array_test_1);

        $dn->add('foo.bar', ['test' => 456]);

        $this->assertTrue($dn->have('foo.bar.test'));
        $this->assertTrue($dn->have('foo.bar.0'));


        $dn->add('foo.bar', ['last' => 789]);
        $this->assertTrue($dn->have('foo.bar.test'));
        $this->assertTrue($dn->have('foo.bar.0'));
        $this->assertTrue($dn->have('foo.bar.last'));

        $this->assertTrue($dn->get('foo.bar.0') == 123);
        $this->assertTrue($dn->get('foo.bar.test') == 456);
        $this->assertTrue($dn->get('foo.bar.last') == 789);
    }
}