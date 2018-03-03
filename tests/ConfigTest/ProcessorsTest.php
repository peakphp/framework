<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Processors\ArrayProcessor;
use Peak\Config\Processors\CallableProcessor;

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
     * @expectedException \Exception
     */
    function testArrayProcessorException()
    {
        $processor = new ArrayProcessor();
        $processor->process('data');
    }


    /**
     * Test ArrayProcessor
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
        $processor = new ArrayProcessor();
        $processor->process('data');
    }

    /**
     * Test CallableProcessor exception
     * @expectedException \Peak\Config\Exceptions\ProcessorException
     */
    function testCallableProcessorException2()
    {
        $processor = new ArrayProcessor();
        $processor->process(function() {
            $test = 'foo';
        });
    }

}