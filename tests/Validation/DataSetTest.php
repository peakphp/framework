<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\Rule;

require __DIR__.'/../fixtures/validation/datasets.php';

class DataSetTest extends TestCase
{

    function testCreate()
    {
        $dataset = new DataSetExample1();

        $this->assertTrue(empty($dataset->getErrors()));
    }  

    function testValidate()
    {
        $dataset = new DataSetExample1();

        $pass = $dataset->validate([
            'login' => 'bob'
        ]);

        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));

        $pass = $dataset->validate([]);

        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));
    }

    function testValidateEmptyRule()
    {
        $dataset = new DataSetExample4();

        $pass = $dataset->validate([
            'login' => 'bob'
        ]);

        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));

        $pass = $dataset->validate([]);

        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));
    }

    function testValidateRequired()
    {
        $dataset = new DataSetExample2();

        $pass = $dataset->validate([]);

        $this->assertFalse($pass);
        $this->assertFalse(empty($dataset->getErrors()));
    }  

    function testValidateIfNotEmpty()
    {
        $dataset = new DataSetExample3();

        $pass = $dataset->validate([]);
        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));


        $pass = $dataset->validate(['login' => '']);
        $this->assertTrue($pass);
        $this->assertTrue(empty($dataset->getErrors()));

        $pass = $dataset->validate(['login' => 'invalid name']);
        $this->assertFalse($pass);
        $this->assertFalse(empty($dataset->getErrors()));
    }  

}


class CustomRule extends \Peak\Validation\AbstractRule
{
    /**
     * Validate
     * 
     * @param  mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return ($value === 'custom');
    }
}
