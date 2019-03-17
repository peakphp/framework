<?php

use PHPUnit\Framework\TestCase;

class MacroTest extends TestCase
{
    public function testAddMacro()
    {
        $a = new MacroTestA();
        $a->setMacro('myFunc', function() {
            return $this->name;
        });
        $this->assertTrue($a->myFunc() === 'bob');
    }

    public function testHasMacro()
    {
        $a = new MacroTestA();
        $a->setMacro('myFunc', function() {
            return $this->name;
        });
        $this->assertTrue($a->hasMacro('myFunc'));
        $this->assertFalse($a->hasMacro('myFunc2'));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnknownMacro()
    {
        $a = new MacroTestA();
        $a->setMacro('myFunc', function() {
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
