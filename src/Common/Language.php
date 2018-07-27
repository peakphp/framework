<?php

declare(strict_types=1);

namespace Peak\Common;

use Peak\Common\Traits\LoadArrayFiles;

/**
 * Simple language translator based on php array
 */
class Language
{
    use LoadArrayFiles;

    /**
     * Array of translation
     * @var array
     */
    protected $translations = [];

    /**
     * Language abbreviation
     * @var string
     */
    protected $lang;

    /**
     * List of language file loaded
     * @var array
     */
    protected $loaded_files = [];

    /**
     * Set lang abbr if specified
     *
     * @param string $lang
     */
    public function __construct(string $lang = null)
    {
        if (isset($lang)) {
            $this->setLang($lang);
        }
    }

    /**
     * Set the language abbr
     *
     * @param string $lang_abbr
     */
    public function setLang(string $lang_abbr): Language
    {
        $this->lang = trim(strtolower($lang_abbr));
        return $this;
    }

    /**
     * Return current lang abbr ($_lang)
     *
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }
    
    /**
     * Add file(s) to the current translations var
     *
     * @param array $files
     * @param string|null  file basepath string if needed
     * @throws \Exception
     */
    public function addFiles(array $files, string $basepath = null): void
    {
        foreach ($this->getArrayFilesContent($files, $basepath) as $file) {
            $this->addContent($file);
        }
    }

    /**
     * Add content
     *
     * @param array $content
     */
    public function addContent(array $content): Language
    {
        $this->translations = array_merge($this->translations, $content);
        return $this;
    }

    /**
     * Check for translation
     *
     * @param  string  $item translation key name
     * @return boolean
     */
    public function has(string $item): bool
    {
        return (array_key_exists($item, $this->translations));
    }

    /**
     * Translate text
     *
     * @param  string $item
     * @param  string $replaces text replacements
     * @return string
     */
    public function translate(string $item, $replaces = null): string
    {
        $tr = (isset($this->translations[$item])) ? $this->translations[$item] : $item;

        if (isset($replaces)) {
            if (is_array($replaces)) {
                $tr = vsprintf($tr, $replaces);
            } else {
                $tr = sprintf($tr, $replaces);
            }
        }

        return $tr;
    }
}
