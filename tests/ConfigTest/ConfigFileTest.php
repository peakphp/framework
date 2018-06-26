<?php

use PHPUnit\Framework\TestCase;

use Peak\Config\Loader\DefaultLoader;
use Peak\Config\Loader\PhpLoader;
use Peak\Config\Loader\TextLoader;
use Peak\Config\Processor\JsonProcessor;
use Peak\Config\ConfigFile;

class ConfigFileTest extends TestCase
{
    /**
     * Test PhpLoader
     */
    public function testPhpLoader()
    {
        $cf = new ConfigFile(
            FIXTURES_PATH.'/config/arrayfile1.php',
            new PhpLoader()
        );

        $content = $cf->get();

        $this->assertTrue(is_array($content));
        $this->assertTrue(array_key_exists('iam', $content));
    }

    /**
     * TextLoader
     */
    public function testTextLoader()
    {
        $cf = new ConfigFile(
            FIXTURES_PATH.'/config/simple.txt',
            new TextLoader()
        );

        $content = $cf->get();

        $this->assertTrue(is_array($content));
        $this->assertTrue(count($content) == 3);
        $this->assertTrue($content[0] === 'John');
    }

    /**
     * Test DefaultLoader
     */
    public function testDefaultLoader()
    {
        $cf = new ConfigFile(
            FIXTURES_PATH.'/config/jsonfile.json',
            null,
            new JsonProcessor()
        );

        $content = $cf->get();

        $this->assertTrue(is_array($content));
        $this->assertTrue(array_key_exists('widget', $content));
    }

    /**
     * Text automatic detection
     */
    public function testAutomaticDetection()
    {
        $cf = new ConfigFile(FIXTURES_PATH.'/config/jsonfile.json');
        $content = $cf->get();
        $this->assertTrue(is_array($content));
        $this->assertTrue(array_key_exists('widget', $content));

        $cf = new ConfigFile(FIXTURES_PATH.'/config/arrayfile1.php');
        $content = $cf->get();
        $this->assertTrue(is_array($content));
        $this->assertTrue(array_key_exists('iam', $content));

        $cf = new ConfigFile(FIXTURES_PATH.'/config/simple.txt');
        $content = $cf->get();
        $this->assertTrue(is_array($content));
        $this->assertTrue(count($content) == 3);
        $this->assertTrue($content[0] === 'John');
    }

    /**
     * @expectedException Peak\Config\Exception\FileNotFoundException
     */
    public function testFileNotFoundException()
    {
        $cf = new ConfigFile(FIXTURES_PATH.'/config/file_that_do_not_exists.php');
    }

    /**
     * @expectedException Peak\Config\Exception\NoFileHandlersException
     */
    public function testNoFileHandlersException()
    {
        $cf = new ConfigFile(FIXTURES_PATH.'/config/unknown.type');
    }

    /**
     * @expectedException Peak\Config\Exception\LoaderException
     */
    public function testLoaderException()
    {
        $tl = new TextLoader();
        $tl->load(true);
    }
}