<?php

use PHPUnit\Framework\TestCase;

use Peak\Common\Collection\Collection;
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
        $config = $configFactory->loadResource([
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
        $config = $configFactory->loadResource([
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
            $stdConf
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
    }

    /**
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testXmlProcessor()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResource([
            FIXTURES_PATH.'/config/config.xml',
        ]);
        print_r($config);
    }

    /**
     * @expectedException \Peak\Config\Exception\ProcessorException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testProcessorException()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResource([
            FIXTURES_PATH.'/config/empty.php'
        ]);
    }

    /**
     * @expectedException \Peak\Config\Exception\UnknownResourceException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testUnknownResourceException()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResource([
            new \PHPUnit\Util\Test()
        ]);
    }

    /**
     * @expectedException \Peak\Config\Exception\NoFileHandlersException
     * @throws \Peak\Config\Exception\UnknownResourceException
     */
    public function testNoFileHandlersException()
    {
        $configFactory = new ConfigFactory();
        $config = $configFactory->loadResource([
            FIXTURES_PATH.'/config/unknown.type',
        ]);
    }

}