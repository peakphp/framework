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

    public function testHelperResolverContainer()
    {
        $viewBuilder = new ViewBuilder(new \Peak\View\HelperResolver(new \Peak\Di\Container()));
        $viewBuilder->setHelper('myHelperFn', ViewHelperA::class);
        $viewBuilder->setPresentation($this->createMock(Presentation::class));
        $view = $viewBuilder->build();
        $this->assertInstanceOf(View::class, $view);
    }

    public function testHelperException()
    {
        $this->expectException(\Peak\View\Exception\InvalidHelperException::class);
        $viewBuilder = new ViewBuilder();
        $viewBuilder->setHelper('myHelperFn', 12222);
        $viewBuilder->setPresentation($this->createMock(Presentation::class));
        $viewBuilder->build();
    }

    public function testHelperExceptionGetHelper()
    {
        try {
            $viewBuilder = new ViewBuilder();
            $viewBuilder->setHelper('myHelperFn', 12222);
            $viewBuilder->setPresentation($this->createMock(Presentation::class));
            $viewBuilder->build();
        } catch (\Peak\View\Exception\InvalidHelperException $e) {
            $value = $e->getHelper();
            $this->assertTrue($value == 12222);
        }

    }
}
