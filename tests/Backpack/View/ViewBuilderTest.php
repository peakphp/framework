<?php

use \PHPUnit\Framework\TestCase;
use \Peak\Backpack\View\ViewBuilder;
use \Peak\View\Presentation;
use \Peak\View\View;

require_once FIXTURES_PATH.'/view/helpers/ViewHelperA.php';

class ViewBuilderTest extends TestCase
{
    public function testBuild()
    {
        $viewBuilder = new ViewBuilder();
        $viewBuilder
            ->setPresentation($this->createMock(Presentation::class))
            ->setVars(['foo' => 'bar'])
            ->setMacro('macro1', function() {
                return 'Hello';
            })
            ->setMacros([
                'macro1' => function() {
                    return 'Hello';
                }
            ])
            ->setHelper('myHelperFn', ViewHelperA::class)
            ->setHelpers([
                'myHelperFn' => ViewHelperA::class
            ]);

        $view = $viewBuilder->build();
        $this->assertInstanceOf(View::class, $view);
    }

    public function testBuildPresentationException()
    {
        $this->expectException(\Exception::class);
        $viewBuilder = new ViewBuilder();
        $viewBuilder->build();
    }

    public function testHelperException()
    {
        $this->expectException(\Peak\View\Exception\InvalidHelperException::class);
        $viewBuilder = new ViewBuilder();
        $viewBuilder->setHelper('myHelperFn', 12222);
        $viewBuilder->setPresentation($this->createMock(Presentation::class));
        $viewBuilder->build();
    }
}
