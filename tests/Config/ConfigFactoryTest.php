<?php

use PHPUnit\Framework\TestCase;

use Peak\Collection\Collection;
use Peak\Config\Config;
use Peak\Config\ConfigFactory;
use Peak\Config\Stream\DataStream;
use Peak\Config\Stream\JsonStream;
use Peak\Config\Processor\JsonProcessor;

class ConfigFactoryTest extends TestCase
{
    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    function testLoadConfig()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            [
                'foo' => 'bar',
                'bar' => 'foo',
                'its' => [
                    'foo' => 'bar'
                ]
            ],
        ]);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->foo === 'bar');
        $this->assertTrue($config->get('bar') === 'foo');
        $this->assertTrue($config->get('its.foo') === 'bar');
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testLoadComplexConfig()
    {
        $stdConf = new stdClass();
        $stdConf->name = 'bob';
        $stdConf->address = new stdClass();
        $stdConf->address->street = 'Foo Boulevard';

        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            // array
            [
                'foo' => 'bar',
                'bar' => 'foo',
                'its' => [
                    'foo' => 'bar'
                ]
            ],
            // json with generic DataStream
            new DataStream(
                '{"foo2": "bar2", "bar2" : "foo2"}',
                new JsonProcessor()
            ),
            // json with JsonStream
            new JsonStream('{"foo3": "bar3", "bar3" : "foo3"}'),
            // collection
            new Collection(['foo4' => 'bar4']),
            // closure
            function() {
                return ['foo5' => 'bar5'];
            },
            // files with auto detection
            FIXTURES_PATH.'/config/arrayfile1.php',
            FIXTURES_PATH.'/config/arrayfile2.php',
            FIXTURES_PATH.'/config/config.ini',
            FIXTURES_PATH.'/config/simple.txt',
            [
                'foo' => 'barbarbar'
            ],
            FIXTURES_PATH.'/config/simple2.txt',
            FIXTURES_PATH.'/config/simple.txt',
            FIXTURES_PATH.'/config/simple3.txt',
            FIXTURES_PATH.'/config/cli.yml',
            $stdConf,
            FIXTURES_PATH.'/config/.env',
        ]);

        $this->assertInstanceOf(Config::class, $config);
        $this->assertTrue($config->foo === 'barbarbar');
        $this->assertTrue($config->get('bar') === 'foo');
        $this->assertTrue($config->get('its.foo') === 'bar');

        $this->assertTrue($config->foo2 === 'bar2');
        $this->assertTrue($config->foo3 === 'bar3');
        $this->assertTrue($config->foo4 === 'bar4');
        $this->assertTrue($config->foo5 === 'bar5');
        $this->assertTrue($config->has('all.php.date.timezone'));
        $this->assertTrue(isset($config[0]));
        $this->assertTrue($config->name === 'bob');
        $this->assertTrue($config->has('ENV'));
        $this->assertFALSE($config->has('COMMENTED_STUFF'));
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testXmlProcessor()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            FIXTURES_PATH.'/config/config.xml',
        ]);
        $this->assertTrue($config->has('@attributes.bootstrap'));
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testProcessorException()
    {
        $this->expectException(\Peak\Config\Exception\ProcessorException::class);
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            FIXTURES_PATH.'/config/empty.php'
        ]);
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testUnknownResourceException()
    {
        $this->expectException(\Peak\Config\Exception\UnknownResourceException::class);
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            new \PHPUnit\Util\Test()
        ]);
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testUnknownResourceExceptionGetResource()
    {
        try {
            $configFactory = new ConfigFactory();
            $config = $configFactory->loadResources([
                new \PHPUnit\Util\Test()
            ]);
        } catch(\Peak\Config\Exception\UnknownResourceException $e) {
            $this->assertTrue($e->getResource() instanceof \PHPUnit\Util\Test);
        }

    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testNoFileHandlersException()
    {
        $this->expectException(\Peak\Config\Exception\NoFileHandlersException::class);
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResources([
            FIXTURES_PATH.'/config/unknown.type',
        ]);
    }

}