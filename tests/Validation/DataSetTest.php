<?php

use PHPUnit\Framework\TestCase;
use Peak\Validation\DataSet;


class DataSetTest extends TestCase
{
    public function testValidation()
    {
        $dataSet = new DataSet([
            'login' => [
                'required', [
                    'rule'  => 'IsNotEmpty',
                    'error' => 'Name is empty',
                ]
            ],
            'pin' => [
                'if_not_empty', [
                    'rule'  => \Peak\Validation\Rule\IntegerNumber::class,
                    'options' => [
                        'min' => 1,
                        'max' => 1000
                    ],
                    'error' => 'Pin must be a number between 1 and ',
                ]
            ]
        ]);

        $this->assertTrue($dataSet->validate([
            'login' => 'bob'
        ]));

        $this->assertFalse($dataSet->validate([
            'login' => ''
        ]));

        $this->assertFalse($dataSet->validate([
            'login' => null
        ]));

        $this->assertFalse($dataSet->validate([
            'login' => []
        ]));

        $this->assertFalse($dataSet->validate([
            'name' => 'bob'
        ]));
    }
}

