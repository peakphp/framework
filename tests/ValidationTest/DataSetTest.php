<?php

use PHPUnit\Framework\TestCase;

use Peak\Validation\Rule;

class DataSetTest extends TestCase
{

    function testCreate()
    {
        $dataset = new DataSetExample();

        $errors = $dataset->getErrors();

        $this->assertTrue(empty($errors));
    }  

    function testValidate()
    {
        $dataset = new DataSetExample();

        $pass = $dataset->validate([
            'login' => 'custom'
        ]);

        $errors = $dataset->getErrors();

        $this->assertTrue($pass);
        $this->assertTrue(empty($errors));
    }    

}

class DataSetExample extends \Peak\Validation\DataSet
{
    // public function __construct()
    // {
    //     echo 'test';
    // }

    public function setUp()
    {

        $this->add('login',
            [
                'rule'  => 'IsNotEmpty',
                'error' => 'Name is empty',
            ],
            [
                'rule'  => 'AlphaNum',
                'error' => 'Only characters and numbers',
            ],
            [
                'rule'    => 'StrLength',
                'options' => [
                    'min' => 4,
                    'max' => 10
                ],
                'error' => 'Name must be between 4 and 10 chars',
            ],
            [
                'rule'  => 'CustomRule',
                'error' => 'Invalid Data',
            ]);
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
    public function validate($value)
    {
        return ($value === 'custom');
    }
}
