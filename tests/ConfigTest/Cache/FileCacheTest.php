<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Cache\FileCache;

class FileCacheTest extends TestCase
{
    private $cachePath = __DIR__.'/tmp';

    /**
     * @expectedException \Peak\Config\Exception\CachePathNotFoundException
     */
    public function testCachePathNotFoundException()
    {
        new FileCache('unknown/path/a/z/w');
    }

    /**
     * @expectedException \Peak\Config\Exception\CacheInvalidKeyException
     */
    public function testCacheInvalidKeyException()
    {
        $fileCache = new FileCache($this->cachePath);
        $fileCache->get(array());
    }

    public function testClear()
    {
        $cacheFile = $this->cachePath.'/myfile.ser';
        file_put_contents($cacheFile, 'foobar');

        $fileCache = new FileCache($this->cachePath);
        $this->assertTrue(file_exists($cacheFile));

        $this->assertTrue($fileCache->clear());
        $this->assertFalse(file_exists($cacheFile));
    }

    public function testDeleteMultiple()
    {
        $id1 = 'cache1';
        $id2 = 'cache2';

        $fileCache = new FileCache($this->cachePath);
        $this->assertFalse($fileCache->deleteMultiple([$id1, $id2]));

        $this->assertTrue($fileCache->set($id1, 'foobar'));
        $this->assertFalse($fileCache->deleteMultiple([$id1, $id2]));

        $this->assertTrue($fileCache->setMultiple([$id1 => 'foobar', $id2 => 'foobar']));
        $this->assertTrue($fileCache->deleteMultiple([$id1, $id2]));
    }

    public function testSetMultiple()
    {
        $id1 = 'cache1';
        $id2 = 'cache2';

        $fileCache = new FileCache($this->cachePath);
        $this->assertTrue($fileCache->setMultiple([
            $id1 => 'test1',
            $id2 => 'test2'
        ], -100));
        $this->assertTrue($fileCache->has($id1));
        $this->assertTrue($fileCache->has($id2));
        $this->assertTrue($fileCache->isExpired($id1));
        $this->assertTrue($fileCache->isExpired($id2));
        $this->assertTrue($fileCache->clear());
    }


    public function testGet()
    {
        $fileCache = new FileCache($this->cachePath);
        $this->assertTrue($fileCache->get('cache1', 'bar') === 'bar');
        $fileCache->set('cache1', 'foo');
        $this->assertTrue($fileCache->get('cache1', 'bar') === 'foo');
        $this->assertTrue($fileCache->clear());
    }

}