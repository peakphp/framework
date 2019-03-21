<?php


use PHPUnit\Framework\TestCase;

class UpdateToCamelCaseTest extends TestCase
{
    public function testUpdate()
    {
        $ucc = new UpdateToCamelCase();
        $original = [
            '_funky-key' => 'foo',
            'test-test' => 'bar',
            'CAP' => 'letter',
            'Hello there' => 'almost'
        ];
        $final = [
            'funkyKey' => 'foo',
            'testTest' => 'bar',
            'cap' => 'letter',
            'helloThere' => 'almost',
        ];
        $result = $ucc->update($original);
        $this->assertTrue($result === $final);

    }
}

class UpdateToCamelCase {
    use \Peak\Common\Traits\UpdateToCamelCase;

    public function update(array $data)
    {
        return $this->updateArrayToCamelCase($data);
    }
}