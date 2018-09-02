<?php

class AbstractDataSetExample1 extends \Peak\Validation\AbstractDataSet
{
    public function setUp()
    {

        $this->add('login', [
            'rule'  => 'IsNotEmpty',
            'error' => 'Name is empty',
        ]);
    }
}


class AbstractDataSetExample2 extends \Peak\Validation\AbstractDataSet
{
    public function setUp()
    {

        $this->add('login', 'required', [
            'rule'  => 'IsNotEmpty',
            'error' => 'Name is empty',
        ]);
    }
}


class AbstractDataSetExample3 extends \Peak\Validation\AbstractDataSet
{
    public function setUp()
    {

        $this->add('login', 'if_not_empty', [
            'rule'  => 'AlphaNum',
            'error' => 'Name is not valid',
        ]);
    }
}

class AbstractDataSetExample4 extends \Peak\Validation\AbstractDataSet
{
    public function setUp()
    {
    }
}