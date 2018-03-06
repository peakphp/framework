<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection;
use Peak\Config\ConfigCache;
use Peak\Common\TimeExpression;


class ConfigCacheTest extends TestCase
{
    protected $path;

    protected $config_example = ['foo' => 'bar', 'imgs' => ['img1.jpg', 'img2.jpg'], 'props' => ['key' => 'val', 'key2' => ['key' => 'val']]];

    public function setUp()
    {
        $this->path = FIXTURES_PATH.'/cache/config';
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testHas()
    {
        $cc = new ConfigCache($this->path);
        $result = $cc->has('my-cache-id');
        $this->assertFalse($result);
    }

    /**
     * @expectedException \Peak\Config\Exceptions\CachePathNotFoundException
     */
    public function testPathNotFoundException()
    {
        $cc = new ConfigCache(FIXTURES_PATH.'/unknown_path');
    }

    /**
     * @expectedException \Peak\Config\Exceptions\InvalidCacheKeyException
     */
    public function testInvalidCacheKeyException()
    {
        $cc = new ConfigCache($this->path);
        $cc->has('invalid{}key-name');
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testGet()
    {
        $cache_id = 'cache-get-test';

        $cc = new ConfigCache($this->path);

        $cc->set(
            $cache_id,
            $this->config_example,
            (new TimeExpression('10y'))->toSeconds()
        );

        $data = $cc->get($cache_id);

        $this->assertTrue($data === $this->config_example);
    }

    public function testGetDefault()
    {
        $cache_id = 'cache-get-test-exception';
        $cc = new ConfigCache($this->path);
        $data = $cc->get($cache_id, 'default');
        $this->assertTrue($data === 'default');
    }

    /**
     * Test cache not expired
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testSet()
    {
        $cache_id = 'cache-set-test';

        $cc = new ConfigCache($this->path);
        $result = $cc->set(
            $cache_id,
            $this->config_example,
            (new TimeExpression('3y'))->toSeconds()
        );

        $this->assertTrue($result);
        $this->assertTrue($cc->has($cache_id));
        $this->assertFalse($cc->isExpired($cache_id));
    }

    /**
     * Test set multiple
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testSetMultiple()
    {
        $cache_id1 = 'cache-setmultiple-1-test';
        $cache_id2 = 'cache-setmultiple-2-test';
        $cache_id3 = 'cache-setmultiple-3-test';
        $cache_id4 = 'cache-setmultiple-4-test';

        $cc = new ConfigCache($this->path);

        $result = $cc->setMultiple([
            $cache_id1 => $this->config_example,
            $cache_id2 => $this->config_example,
            $cache_id3 => $this->config_example,
            $cache_id4 => $this->config_example,
        ]);

        $this->assertTrue($result);

        $this->assertTrue($cc->has($cache_id1));
        $this->assertTrue($cc->has($cache_id2));
        $this->assertTrue($cc->has($cache_id3));
        $this->assertTrue($cc->has($cache_id4));
    }

    /**
     * Test cache expired
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testIsExpired()
    {
        $cache_id = 'cache-expired-test';

        $cc = new ConfigCache($this->path);
        $cc->set(
            $cache_id,
            $this->config_example,
            -500
        );

        $this->assertTrue($cc->has($cache_id));
        $this->assertTrue($cc->isExpired($cache_id));
    }

    /**
     * Test delete
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testDelete()
    {
        $cache_id = 'cache-todelete-test';
        $cc = new ConfigCache($this->path);
        $cc->set(
            $cache_id,
            $this->config_example
        );

        $this->assertTrue($cc->delete($cache_id));
        $this->assertFalse($cc->has($cache_id));
        $this->assertFalse($cc->delete('unknown-cache-id'));
    }

    /**
     * Test delete multiple
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function testDeleteMultiple()
    {
        $cache_id1 = 'cache-deletemultiple-1-test';
        $cache_id2 = 'cache-deletemultiple-2-test';
        $cache_id3 = 'cache-deletemultiple-3-test';
        $cache_id4 = 'cache-deletemultiple-4-test';

        $cc = new ConfigCache($this->path);
        $cc->setMultiple([
            $cache_id1 => $this->config_example,
            $cache_id2 => $this->config_example,
            $cache_id3 => $this->config_example,
        ]);

        $this->assertTrue($cc->deleteMultiple([$cache_id1, $cache_id2, $cache_id3]));
        $this->assertFalse($cc->has($cache_id1));
        $this->assertFalse($cc->has($cache_id2));
        $this->assertFalse($cc->has($cache_id3));

        //test false
        $cc->setMultiple([
            $cache_id1 => $this->config_example,
            $cache_id2 => $this->config_example,
            $cache_id3 => $this->config_example,
        ]);

        $this->assertFalse($cc->deleteMultiple([$cache_id1, $cache_id4, $cache_id2, $cache_id3]));
        $this->assertFalse($cc->has($cache_id1));
        $this->assertFalse($cc->has($cache_id2));
        $this->assertFalse($cc->has($cache_id3));
    }

    /**
     * @throws \Peak\Config\Exceptions\UnknownTypeException
     */
    public function testWithConfigLoader()
    {
        $cache_id = 'cache-configloader-test';
        $cc = new ConfigCache($this->path);

        if ($cc->isExpired($cache_id)) {
            $data = (new \Peak\Config\ConfigLoader([
                FIXTURES_PATH.'/config/simple.txt',
                new Collection([
                    'foo' => 'bar'
                ]),
                new Collection(['foo' => 'bar']),
                FIXTURES_PATH.'/config/arrayfile1.php',
                FIXTURES_PATH.'/config/config.yml',
                ['array' => 'hophop'],
                function() {
                    return ['anonym' => 'function'];
                },
            ]))->asCollection();

            $cc->set($cache_id, $data, 300);
        }

        $data = $cc->get($cache_id);

        $this->assertTrue($data instanceof Collection);
    }

    /**
     * Test clear
     */
    public function testClear()
    {
        $cc = new ConfigCache($this->path);
        $cc->clear();
        $count = 0;
        $dir = new \DirectoryIterator($this->path);
        foreach ($dir as $file) {
            if (!$file->isDot()) {
                ++$count;
            }
        }
        $this->assertTrue($count == 1);
    }




}