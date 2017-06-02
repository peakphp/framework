<?php

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
    public function __construct($lang = null)
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
    public function setLang($lang_abbr)
    {
        $this->lang = trim(strtolower($lang_abbr));
        return $this;
    }

    /**
     * Return current lang abbr ($_lang)
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
    
    /**
     * Add file(s) to the current translations var
     *
     * @param array $files
     * @param string|null  file basepath string if needed
     */
    public function addFiles($files, $basepath = null)
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
    public function addContent(array $content)
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
    public function has($item)
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
    public function translate($item, $replaces = null)
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
