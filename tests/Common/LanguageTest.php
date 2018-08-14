<?php

use PHPUnit\Framework\TestCase;

class LanguageTest extends TestCase
{
    /**
     * Create object
     */
    public function testLanguageBasic()
    {       
        $lang = new \Peak\Common\Language('fr');

        $this->assertTrue($lang->getLang() === 'fr');
        $lang->setLang('en');
        $this->assertTrue($lang->getLang() === 'en');
    }

    /**
     * Add content
     */
    public function testAddContent()
    {       
        $lang = new \Peak\Common\Language('fr');

        $translations = [
            'error-file-not-found' => 'Fichier %s introuvable'
        ];

        $this->assertTrue($lang->getLang() === 'fr');
        $lang->addContent($translations);
        $this->assertTrue($lang->has('error-file-not-found'));
        $this->assertFalse($lang->has('error-folder-not-found'));
    }

    /**
     * Translate
     */
    public function testTranslate()
    {       
        $lang = new \Peak\Common\Language('fr');

        $translations = [
            'error-file-not-found' => 'Fichier %s introuvable'
        ];

        $lang->addContent($translations);
        $this->assertTrue($lang->translate('error-file-not-found') === $translations['error-file-not-found']);
        $this->assertTrue($lang->translate('error-file-not-found', ['myfile']) === 'Fichier myfile introuvable');

        $this->assertTrue($lang->translate('unknow string...') === 'unknow string...');
    }

    /**
     * Add files
     */
    public function testAddFiles()
    {       
        $lang = new \Peak\Common\Language('fr');

        $lang->addFiles([
            'arrayfile1.php',
            'arrayfile2.php'
        ], __DIR__.'/../fixtures/config');

        $this->assertTrue($lang->translate('iam') === 'arrayfile2');
    }   
}