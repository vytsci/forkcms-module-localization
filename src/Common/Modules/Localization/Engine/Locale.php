<?php

namespace Common\Modules\Localization\Engine;

/**
 * Class Locale
 * @package Backend\Modules\Localization\Engine
 */
class Locale
{

    /**
     * Languages list.
     *
     * @var array
     */
    private $languages = array();

    /**
     * Is locale currently used withing loop? We need to return current language only if loop was started
     *
     * @var bool
     */
    private $loop = false;

    /**
     * Sets up given languages. If $languages array is empty all active languages will be loaded.
     *
     * @param array $languages Array of languages to set up. Example: array('en', 'ru', 'lt')
     */
    function __construct($languages = array())
    {
        foreach ($languages as $languagesValue) {
            $this->setLanguage($languagesValue);
        }

        $this->resetLanguage();
    }

    /**
     * Marks that loop was started ant return current language
     * @todo remake this so we wont need nextLanguage within while loop
     *
     * @return null|Language
     */
    public function loopLanguage()
    {
        $this->loop = true;
        $result = $this->currentLanguage();

        /* If result is null that means we reached end of a loop and we must reset it automatically */
        if (null === $result) {
            $this->resetLanguage();
        }

        return empty($result) ? null : $result;
    }

    /**
     * Returns current language if loop was started and if not returns null
     *
     * @return null|Language
     */
    public function currentLanguage()
    {
        if (false === $this->loop) {
            return null;
        }

        $result = current($this->languages);

        return empty($result) ? null : $result;
    }

    /**
     * Advances to next language
     *
     * @return mixed
     */
    public function nextLanguage()
    {
        $result = next($this->languages);

        return empty($result) ? null : $result;
    }

    /**
     * Resets pointer to first language
     *
     * @return mixed
     */
    public function resetLanguage()
    {
        $this->loop = false;
        reset($this->languages);

        return $this->currentLanguage();
    }

    /**
     * Gets language code and title pairs.
     *
     * @return array
     */
    public function getLanguagesPairs()
    {
        $result = array();

        while ($language = $this->loopLanguage()) {
            $result[$language->getCode()] = array(
                'code' => $language->getCode(),
                'title' => $language->getTitle(),
            );
            $this->nextLanguage();
        }

        $this->resetLanguage();

        return $result;
    }

    /**
     * Gets raw languages array values are lt,en,ru,etc
     *
     * @return array
     */
    public function getLanguagesRaw()
    {
        $result = array_keys($this->languages);

        return $result;
    }

    /**
     * Gets all languages.
     *
     * @return array
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Gets language object based on code.
     *
     * @param $language
     * @return mixed
     * @throws \Exception
     */
    public function getLanguage($language)
    {
        if (empty($language) || !$this->existsLanguage($language)) {
            throw new \Exception('Such language does not exist');
        }

        return $this->languages[$language];
    }

    /**
     * Checks if language exists.
     *
     * @param string $language Language code. Example: 'en'
     * @return bool
     */
    public function existsLanguage($language)
    {
        if (empty($language)) {
            return false;
        }

        return isset($this->languages[$language]);
    }

    /**
     * Sets up new language object based on code.
     *
     * @param Language $language
     * @return $this
     */
    public function setLanguage(Language $language)
    {
        $this->languages[$language->getCode()] = $language;

        return $this;
    }

    /**
     * Maps useful modifiers
     *
     * @param \SpoonTemplate $tpl
     */
    public function parse(\SpoonTemplate $tpl)
    {
        Helper::mapTemplateModifiers($tpl);
    }
}
