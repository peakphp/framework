<?php

declare(strict_types=1);

namespace Peak\Validation;

use Peak\Validation\Definition\RuleArrayDefinition;

/**
 * Validation rules for a data set
 */
class DataSet extends AbstractDataSet
{
    /**
     * @var array
     */
    private $rulesArray;

    /**
     * DataSet constructor.
     */
    public function __construct(array $rulesArray)
    {
        $this->rulesArray = $rulesArray;
        parent::__construct();
    }

    /**
     * SetUp data rules
     */
    public function setUp()
    {
        foreach ($this->rulesArray as $dataKey => $definitions) {
            foreach ($definitions as $def) {
                $this->add($dataKey, $def);
            }
        }
    }
}
