<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Cache\FileCache;

class FileCacheTest extends TestCase
{
    /**
     * @expectedException \Peak\Config\Exception\CachePathNotFoundException
     */
    public function testCachePathNotFoundException()
    {
        new FileCache('unknown/path/a/z/w');
    }

    public function testClear()
    {
        $cachePath = __DIR__.'/tmp';
        $cacheFile = $cachePath.'/myfile.ser';
        file_put_contents($cacheFile, 'foobar');

        $fileCache = new FileCache($cachePath);
        $this->assertTrue(file_exists($cacheFile));

        $this->assertTrue($fileCache->clear());
        $this->assertFalse(file_exists($cacheFile));
    }

}