<?php

use PHPUnit\Framework\TestCase;

class MacroTest extends TestCase
{
    public function testGetArrayFilesContent()
    {
        $a = new MacroTestA();
        $a->addMacro('myFunc', function() {
            return $this->name;
        });
        $this->assertTrue($a->myFunc() === 'bob');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnknownMacro()
    {
        $a = new MacroTestA();
        $a->addMacro('myFunc', function() {
            return $this->name;
        });
        $this->assertTrue($a->myFunc2() === 'bob');
    }
}


class MacroTestA
{
    private $name = 'bob';
    use \Peak\Common\Traits\Macro;

    public function __call(string $macroName, array $args)
    {
        return $this->callMacro($macroName, $args);
    }
}
