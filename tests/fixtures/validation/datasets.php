<?php

class DataSetExample1 extends \Peak\Validation\DataSet
{
    public function setUp()
    {

        $this->add('login', [
            'rule'  => 'IsNotEmpty',
            'error' => 'Name is empty',
        ]);
    }
}


class DataSetExample2 extends \Peak\Validation\DataSet
{
    public function setUp()
    {

        $this->add('login', 'required', [
            'rule'  => 'IsNotEmpty',
            'error' => 'Name is empty',
        ]);
    }
}


class DataSetExample3 extends \Peak\Validation\DataSet
{
    public function setUp()
    {

        $this->add('login', 'if_not_empty', [
            'rule'  => 'AlphaNum',
            'error' => 'Name is not valid',
        ]);
    }
}

class DataSetExample4 extends \Peak\Validation\DataSet
{
    public function setUp()
    {
    }
}