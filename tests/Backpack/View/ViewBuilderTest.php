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

    /**
     * @expectedException \Exception
     */
    public function testBuildPresentationException()
    {
        $viewBuilder = new ViewBuilder();
        $viewBuilder->build();
    }
}
