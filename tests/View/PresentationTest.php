<?php

use \PHPUnit\Framework\TestCase;

use Peak\View\Presentation;

class PresentationTest extends TestCase
{
    public function testGetSources()
    {
        $scripts = [
            '/layout.php' => [
                '/script.php',
                '/layout2,php' => [
                    '/script2.php',
                    '/script3.php',
                ]
            ]
        ];
        $presentation = new Presentation($scripts);

        $this->assertTrue(is_array($presentation->getSources()));
        $this->assertTrue($presentation->getSources() === $scripts);
    }

    public function testSetPath()
    {
        $scripts = [
            '/layout.php' => [
                '/script.php',
                '/layout2,php' => [
                    '/script2.php',
                    '/script3.php',
                ]
            ]
        ];

        $scriptsWithPath = [
            __DIR__.'/layout.php' => [
                __DIR__.'/script.php',
                __DIR__.'/layout2,php' => [
                    __DIR__.'/script2.php',
                    __DIR__.'/script3.php',
                ]
            ]
        ];

        $presentation = new Presentation($scripts, __DIR__);
        $this->assertTrue($presentation->getSources() === $scriptsWithPath);
    }
}
