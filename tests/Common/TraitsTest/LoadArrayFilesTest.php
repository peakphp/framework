<?php

use PHPUnit\Framework\TestCase;

class LoadArrayFilesTest extends TestCase
{
    public function testGetArrayFilesContent()
    {
        $a = new A;

        $content = $a->getContent([
            'arrayfile1.php',
            'arrayfile2.php'
        ], __DIR__.'/../../fixtures/config');

        $this->assertTrue(is_array($content));
        $this->assertTrue(count($content) == 2);
        $this->assertTrue(isset($content[0]['iam']) && $content[0]['iam'] === 'arrayfile1');
        $this->assertTrue(isset($content[1]['iam']) && $content[1]['iam'] === 'arrayfile2');
    }

    public function testExceptionFileNotFound()
    {
        $a = new A;

        try {
            $content = $a->getContent([
                'arrayfile2.php',
                'arrayfile18.php'
            ], __DIR__.'/../../fixtures/config');

        } catch(\Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }

    public function testExceptionFileNotAnArray()
    {
        $a = new A;

        try {
            $content = $a->getContent([
                'arrayfile2.php',
                'empty.php'
            ], __DIR__.'/../../fixtures/config');

        } catch(\Exception $e) {
            $error = true;
        }

        $this->assertTrue(isset($error));
    }
}


class A
{
    use \Peak\Common\Traits\LoadArrayFiles;

    public function getContent($files, $basepath = null)
    {
        return $this->getArrayFilesContent($files, $basepath);
    }
}
