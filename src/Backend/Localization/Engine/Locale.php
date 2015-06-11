<?php

namespace Backend\Modules\Localization\Engine;

use Backend\Core\Engine\Language as BL;
use Backend\Core\Engine\Model as BackendModel;

use Backend\Modules\Localization\Engine\Helper as BackendLocalizationHelper;

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
        if (empty($languages) || !is_array($languages)) {
            $languages = BL::getActiveLanguages();
        }

        foreach ($languages as $languagesValue) {
            $this->setLanguage($languagesValue);
        }

        if (!$this->existsLanguage(BL::getWorkingLanguage())) {
            $this->setLanguage(BL::getWorkingLanguage());
        }

        $this->resetLanguage();
    }

    /**
     * Marks that loop was started ant return current language
     *
     * @return null|Language
     */
    public function loopLanguage()
    {
        $this->loop = true;
        $result = $this->currentLanguage();

        /* If result is null that means we reached end of a loop and we reset it automatically */
        if (null === $result) {
            $this->resetLanguage();
        }

        return empty($result)?null:$result;
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

        return empty($result)?null:$result;
    }

    /**
     * Advances to next language
     *
     * @return mixed
     */
    public function nextLanguage()
    {
        $result = next($this->languages);

        return empty($result)?null:$result;
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
                'title' => $language->getTitle()
            );
            $this->nextLanguage();
        }

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
     * @param string $language Language code. Example: 'en'
     * @return Language
     */
    public function getLanguage($language)
    {
        if (empty($language)) {
            return null;
        }

        if (!$this->existsLanguage($language)) {
            $this->setLanguage($language);
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
     * @param string $language Language code. Example: 'en'
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->languages[$language] = new Language($language);

        return $this;
    }

    /**
     * Maps useful modifiers
     *
     * @param \SpoonTemplate $tpl
     */
    public function parse(\SpoonTemplate $tpl)
    {
        BackendLocalizationHelper::mapTemplateModifiers($tpl);
    }
}
