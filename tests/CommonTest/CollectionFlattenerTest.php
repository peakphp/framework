<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection;
use Peak\Common\CollectionFlattener;

class CollectionFlattenerTest extends TestCase
{

    protected $array = [
        'foo' => 'bar',
        'project' => [
            'name' => 'myproj',
            'desc' => 'myproj desc',
            'version' => '1.0',
            'author' => 'bob',
        ],
        'pages' => [
            'home',
            'about',
            'blog',
            'contact',
            'services',
        ],
        'level1' => [
            'level1.A' => 'foo',
            'level2' => [
                'level3' => [
                    'level3.A' => '1234',
                    'level4' => [
                        'level5' => [
                            'level6' => [
                                'bar'
                            ],
                        ],
                    ],
                ]
            ]
        ]
    ];


    /**
     * test new instance and flat all
     */
    function testClass()
    {
        $collection = new Collection($this->array);

        $cf = new CollectionFlattener($collection);
        $array = $cf->flatAll();

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) == 13);
        $this->assertTrue(array_key_exists('level1.level2.level3.level4.level5.level6.0', $array));
        $this->assertTrue(array_key_exists('project.author', $array));
        $this->assertFalse(array_key_exists('project.website', $array));
    }

    /**
     * test flat key
     */
    function testFlatKey()
    {
        $collection = new Collection($this->array);

        $cf = new CollectionFlattener($collection);

        $array = $cf->flatKey('project'); // their is no value (non-array) for key project
        $this->assertTrue(empty($array));

        $array = $cf->flatKey('foo');
        $this->assertTrue(count($array) == 1);
        $this->assertTrue($array['foo'] === 'bar');

        $array = $cf->flatKey('project.*'); // return project keys
        $this->assertTrue(count($array) == 4);

        $array = $cf->flatKey('.*'); // return all
        $this->assertTrue(count($array) == 13);
    }

    /**
     * test flat keys
     */
    function testFlatKeys()
    {
        $collection = new Collection($this->array);

        $cf = new CollectionFlattener($collection);

        $array = $cf->flatKeys(['project']); // their is no value (non-array) for key project
        $this->assertTrue(empty($array));

        $array = $cf->flatKeys(['foo']);
        $this->assertTrue(count($array) == 1);
        $this->assertTrue($array['foo'] === 'bar');

        $array = $cf->flatKeys(['project.*']); // return project keys
        $this->assertTrue(count($array) == 4);

        $array = $cf->flatKeys(['.*']); // return all
        $this->assertTrue(count($array) == 13);

        $array = $cf->flatKeys(['project.*', 'pages.*']); // return project and pages
        $this->assertTrue(count($array) == 9);
        $this->assertFalse(array_key_exists('foo', $array));
        $this->assertTrue(array_key_exists('pages.0', $array));
    }

    /**
     * test separator
     */
    function testSeparator()
    {
        $collection = new Collection($this->array);

        $cf = new CollectionFlattener($collection);
        $array = $cf->separator(',')->flatAll();

        $this->assertTrue(is_array($array));
        $this->assertTrue(count($array) == 13);
        $this->assertTrue(array_key_exists('project,author', $array));
    }

    /**
     * test separator exception
     */
    function testSeparatorException()
    {
        $error1 = false;
        $error2 = false;

        try {
            $collection = new Collection($this->array);
            $cf = new CollectionFlattener($collection);
            $cf->separator('*');
        } catch(Exception $e) {
            $error1 = true;
        }

        $this->assertTrue($error1);

        try {
            $collection = new Collection($this->array);
            $cf = new CollectionFlattener($collection);
            $cf->separator('__');
        } catch(Exception $e) {
            $error2 = true;
        }

        $this->assertTrue($error2);
    }
}
