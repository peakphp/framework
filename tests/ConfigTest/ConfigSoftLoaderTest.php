<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\ConfigSoftLoader;
use Peak\Common\Collection;

class ConfigSoftLoaderTest extends TestCase
{
    /**
     * Test load as Collection object
     */
    function testLoadFilesAsCollection()
    {
        $col = (new ConfigSoftLoader([
            'config/unknow.php',
            'config/arrayfile1.php',
            'config/jsonfile.json',
        ], FIXTURES_PATH
        ))->asCollection();

        $this->assertTrue($col instanceof Collection);
        $this->assertTrue($col->iam === 'arrayfile1');
    }

    /**
     * Test not found
     */
    function testExceptionFileNotFound()
    {
        try {
            $col = (new ConfigSoftLoader([
                'config/unknow.php',
                'config/unknow2.php',
            ]))->asCollection();
        } catch (Exception $e) {
            $error = true;
        }
        $this->assertFalse(isset($error));
        $this->assertTrue($col->isEmpty());
    }


}