<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\ConfigLoader;
use Peak\Common\DotNotationCollection;
use Peak\Common\Collection;

class ConfigLoaderTest extends TestCase
{
    public $good_files = [
        'config/arrayfile1.php',
        'config/arrayfile2.php',
    ];

    public $good_mixed_files = [
        'config/arrayfile1.php',
        'config/jsonfile.json',
    ];

    public $good_files_inverse = [
        'config/arrayfile2.php',
        'config/arrayfile1.php',
    ];

    public $good_files2 = [
        FIXTURES_PATH.'/config/arrayfile1.php',
        FIXTURES_PATH.'/config/arrayfile2.php',
    ];

    public $not_good_files = [
        FIXTURES_PATH.'/config/arrayfile98.php',
        FIXTURES_PATH.'/config/arrayfile99.php',
    ];

    public $not_good_mixed_files = [
        FIXTURES_PATH.'/config/arrayfile1.php',
        FIXTURES_PATH.'/config/malformed.json',
    ];

    function testLoadFilesAsCollection()
    {
        $col = (new ConfigLoader(
            $this->good_files,
            FIXTURES_PATH
        ))->asCollection();

        $this->assertTrue($col instanceof Collection);
        $this->assertTrue($col->iam === 'arrayfile2');

        $col = (new ConfigLoader($this->good_files2))->asCollection();
        $this->assertTrue($col instanceof Collection);
        $this->assertTrue($col->iam === 'arrayfile2');

        $col = (new ConfigLoader($this->good_files_inverse, FIXTURES_PATH))->asCollection();
        $this->assertTrue($col instanceof Collection);
        $this->assertTrue($col->iam === 'arrayfile1');
    }

    function testLoadFilesAsDotNotationCollection()
    {
        $col = (new ConfigLoader($this->good_files, FIXTURES_PATH))->asDotNotationCollection();
        $this->assertTrue($col instanceof DotNotationCollection);
        $this->assertTrue($col->iam === 'arrayfile2');

        $col = (new ConfigLoader($this->good_files2))->asDotNotationCollection();
        $this->assertTrue($col instanceof DotNotationCollection);
        $this->assertTrue($col->iam === 'arrayfile2');

        $col = (new ConfigLoader($this->good_files_inverse, FIXTURES_PATH))->asDotNotationCollection();
        $this->assertTrue($col instanceof DotNotationCollection);
        $this->assertTrue($col->iam === 'arrayfile1');
    }

    function testLoadFilesAsArray()
    {
        $array = (new ConfigLoader($this->good_files, FIXTURES_PATH))->asArray();
        $this->assertTrue(is_array($array));
        $this->assertTrue($array['iam'] === 'arrayfile2');
    }

    function testLoadFilesAsObject()
    {
        $obj = (new ConfigLoader($this->good_files, FIXTURES_PATH))->asObject();
        $this->assertTrue(is_object($obj));
        $this->assertTrue($obj->iam === 'arrayfile2');
    }

    function testLoadFilesAsClosure()
    {
        $obj = (new ConfigLoader($this->good_files, FIXTURES_PATH))->asClosure(function($coll) {
            return new Peak\Bedrock\Application\Config($coll->toArray());
        });

        $this->assertInstanceOf('Peak\Bedrock\Application\Config', $obj);
        $this->assertTrue($obj->iam === 'arrayfile2');
    }

    function testExceptionFileNotFound()
    {
        try {
            $col = (new ConfigLoader($this->not_good_files))->asCollection();
        } catch (Exception $e) {
            $error = true;
        }
        $this->assertTrue(isset($error));
    }

    function testMixedTypeConfigs()
    {
        $col = (new ConfigLoader([
            new Collection([
                'foo' => 'bar'
            ]),
            '{"foo": "bar2", "bar" : "foo"}',
            new Collection(['foo' => 'bar']),
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/confsig.yml',
            ['array' => 'hophop'],
            function() {
                return ['anonym' => 'function'];
            }
        ]))->asCollection();

        $this->assertTrue($col instanceof Collection);
        $this->assertTrue($col->iam === 'arrayfile1');
        $this->assertTrue($col->foo === 'bar');
        $this->assertTrue($col->bar === 'foo');
        $this->assertTrue($col->array === 'hophop');
        $this->assertTrue($col->anonym === 'function');
        $this->assertTrue(count($col->items) == 2);
        $this->assertTrue(isset($col->items) == 2);
    }
}