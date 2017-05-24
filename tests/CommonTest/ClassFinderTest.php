<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\ClassFinder;

class ClassFInderTest extends TestCase
{
	/**
     * test global chrono start and stop
     */
    function testFindFirst()
    {
        // both namespace contains a Json class
        $cf = new ClassFinder([
            'Peak\Bedrock\View\Render',
            'Peak\Config\File'
        ]);

        $class = $cf->findFirst('Json');
        $this->assertTrue($class === Peak\Bedrock\View\Render\Json::class);

        $class = null;
        $class = $cf->findFirst('Test');
        $this->assertFalse($class);
    }

    /**
     * test global chrono start and stop
     */
    function testFindLast()
    {
        // both namespace contains a Json class
        $cf = new ClassFinder([
            'Peak\Bedrock\View\Render',
            'Peak\Config\File'
        ]);

        $class = $cf->findLast('Json');
        $this->assertTrue($class === Peak\Config\File\Json::class);

        $class = null;
        $class = $cf->findFirst('Test');
        $this->assertFalse($class);
    }
        	  
}