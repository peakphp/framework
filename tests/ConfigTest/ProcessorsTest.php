<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection;
use Peak\Config\Processors\ArrayProcessor;
use Peak\Config\Processors\CallableProcessor;
use Peak\Config\Processors\CollectionProcessor;
use Peak\Config\Processors\IniProcessor;
use Peak\Config\Processors\JsonProcessor;

class ProcessorsTest extends TestCase
{

    /**
     * Test ArrayProcessor
     */
    function testArrayProcessor()
    {
        $processor = new ArrayProcessor();
        $processor->process(['foo' => 'bar']);
        $content = $processor->getContent();

        $this->assertTrue(is_array($content));
    }

    /**
     * Test ArrayProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testArrayProcessorException()
    {
        $processor = new ArrayProcessor();
        $processor->process('data');
    }


    /**
     * Test CallableProcessor
     */
    function testCallableProcessor()
    {
        $processor = new CallableProcessor();

        $processor->process(function() {
            $array1 = ['foo' => 'bar'];
            $array2 = ['bar' => 'foo'];
            return array_merge($array1, $array2);
        });

        $content = $processor->getContent();

        $this->assertTrue(is_array($content));
    }

    /**
     * Test CallableProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testCallableProcessorException()
    {
        $processor = new CallableProcessor();
        $processor->process('data');
    }

    /**
     * Test CallableProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testCallableProcessorException2()
    {
        $processor = new CallableProcessor();
        $processor->process(function() {
            $test = 'foo';
        });
    }

    /**
     * Test CollectionProcessor
     */
    function testCollectionProcessor()
    {
        $processor = new CollectionProcessor();

        $processor->process(new Collection(['foo' => 'bar']));

        $content = $processor->getContent();

        $this->assertTrue(is_array($content));
        $this->assertTrue(isset($content['foo']));
    }

    /**
     * Test CollectionProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testCollectionProcessorException()
    {
        $processor = new CollectionProcessor();
        $processor->process('data');
    }

    /**
     * Test IniProcessor
     */
    function testIniProcessor()
    {
        $processor = new IniProcessor();

        $ini = file_get_contents(FIXTURES_PATH.'/config/config.ini');
        $processor->process($ini);

        $content = $processor->getContent();

        $this->assertTrue(is_array($content));
        $this->assertTrue(isset($content['all']));
    }

    /**
     * Test IniProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testIniProcessorException()
    {
        $processor = new IniProcessor();
        $processor->process('data = ;;;;');
    }

    /**
     * Test IniProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testIniProcessorException2()
    {
        $processor = new IniProcessor();
        $processor->process('[all:php]');
    }

    /**
     * Test JsonProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testJsonProcessorException()
    {
        $jp = new JsonProcessor();
        $jp->process('invalid : ; " json string()');
    }
}