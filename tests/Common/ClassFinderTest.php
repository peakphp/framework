<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\ClassFinder;

class ClassFinderTest extends TestCase
{
	/**
     * test findFirst()
     */
    function testFindFirst()
    {
        // both namespace contains a Json class
        $cf = new ClassFinder([
            'Peak\Bedrock\View\Render',
            'Peak\Config\Processor'
        ]);

        $class = $cf->findFirst('JsonProcessor');
        $this->assertTrue($class === Peak\Config\Processor\JsonProcessor::class);

        $class = null;
        $class = $cf->findFirst('Test');
        $this->assertNull($class);
    }

    /**
     * test findLast()
     */
    function testFindLast()
    {
        // both namespace contains a Json class
        $cf = new ClassFinder([
            'Peak\Bedrock\View\Render',
            'Peak\Config\Processor'
        ]);

        $class = $cf->findLast('JsonProcessor');
        $this->assertTrue($class === Peak\Config\Processor\JsonProcessor::class);

        $class = null;
        $class = $cf->findFirst('Test');
        $this->assertNull($class);
    }

    /**
     * Test suffix() and prefix()
     */
    function testSuffixPrefix()
    {
        $cf = new ClassFinder([
            'Peak\Http'
        ]);

        $cf->setPrefix('');
        $cf->setSuffix('Factory');

        $class = $cf->findLast('Stack');
        $this->assertTrue($class === \Peak\Http\StackFactory::class);
    }
}
