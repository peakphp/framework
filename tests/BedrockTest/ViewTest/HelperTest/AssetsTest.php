<?php

use PHPUnit\Framework\TestCase;

use Peak\Bedrock\View\Helper\Assets;

class AssetsTest extends TestCase
{
    function testInstantiate()
    {
        $path = FIXTURES_PATH.'/assets';
        $url  = '//myurl.url';
        $assets = new Assets($path, $url);
        $this->assertTrue($assets->getPath() === $path);
        $this->assertTrue($assets->getUrl() === $url);
    }

    function testSet()
    {
        $path = FIXTURES_PATH.'/assets';
        $path2 = FIXTURES_PATH.'/assets2';
        $url  = '//myurl.url';
        $url2  = '//myurl2.url';

        $assets = new Assets($path, $url);

        $assets->setPath($path2);
        $assets->setUrl($url2);

        $this->assertTrue($assets->getPath() === $path2);
        $this->assertTrue($assets->getUrl() === $url2);
    }

    function testProcess()
    {
        $path = FIXTURES_PATH.'/assets';
        $url  = '//myurl.url';
        $assets = new Assets($path, $url);

        $asset = $assets->process('css', 'css/theme.css');

        $result_expected = '<link rel="stylesheet" href="//myurl.url/css/theme.css">';
        $this->assertTrue($asset === $result_expected);

        $asset = $assets->css('css/theme.css');
        $this->assertTrue($asset === $result_expected);

        $result_expected = '<link rel="stylesheet" href="//myurl.url/css/theme.css"><link rel="stylesheet" href="//myurl.url/css/extra.css">';
        $asset = $assets->css([
            'css/theme.css',
            'css/extra.css'
        ]);
        $this->assertTrue($asset === $result_expected);


        $asset = $assets->process('css', [
            'css/theme.css',
            'css/extra.css'
        ]);
        $this->assertTrue($asset === $result_expected);

        $result_expected = '<link rel="stylesheet" href="//myurl.url/css/theme.css?v=2">';
        $asset = $assets->process('css','css/theme.css', 'v=2');
        //$asset = $assets->css('css/theme.css');
        $this->assertTrue($asset === $result_expected);


        $result_expected = '<script type="text/javascript" src="//myurl.url/js/main.js"></script><script type="text/javascript" src="//myurl.url/js/extra.js"></script>';
        $asset = $assets->js([
            'js/main.js',
            'js/extra.js'
        ]);
        $this->assertTrue($asset === $result_expected);
    }


    function testProcessAuto()
    {
        $path = FIXTURES_PATH.'/assets';
        $url  = '//myurl.url';
        $assets = new Assets($path, $url);

        $result_expected = '<link rel="stylesheet" href="//myurl.url/css/theme.css"><script type="text/javascript" src="//myurl.url/js/main.js"></script>';
        $asset = $assets->process('auto', [
            'css/theme.css',
            'js/main.js'
        ]);
        $this->assertTrue($asset === $result_expected);

        $asset = $assets->auto([
            'css/theme.css',
            'js/main.js'
        ]);
        $this->assertTrue($asset === $result_expected);
    }

    function testExists()
    {
        $path = FIXTURES_PATH . '/assets';
        $url = '//myurl.url';
        $assets = new Assets($path, $url);

        $this->assertTrue($assets->exists('css/theme.css'));
        $this->assertFalse($assets->exists('css/themes.css'));
    }

}