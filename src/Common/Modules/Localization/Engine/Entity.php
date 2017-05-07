<?php

namespace Common\Modules\Localization\Engine;

use Common\Modules\Entities\Engine\Entity as CommonEntity;

/**
 * Class Entity
 * @package Common\Modules\Localization\Engine
 */
class Entity extends CommonEntity
{

    /**
     * @var string
     */
    protected $_locale;

    /**
     * @var string
     */
    protected $_language;

    /**
     * @var
     */
    protected $_languages = array();

    /**
     * @var array
     */
    protected $locale = array();

    /**
     * @param array $languages
     * @param array $parameters
     */
    function __construct($parameters = array(), $languages = array())
    {
        $this->setLanguages($languages);

        parent::__construct($parameters);
    }

    /**
     * @param $record
     * @param array $languages
     * @return $this
     */
    public function assemble($record, $languages = array())
    {
        if (!empty($languages)) {
            $this->setLanguages($languages);
        }

        parent::assemble($record);

        $this->loadLocale();

        return $this;
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function loadLocale()
    {
        if ($this->hasLanguages()) {
            $languages = $this->getLanguages();

            foreach ($languages as $language) {
                if (isset($this->_locale)) {
                    /* @var $locale CommonEntity */
                    $locale = new $this->_locale(array($this->getId(), $language));
                    $this->setLocale($locale, $language);
                }
            }
            if (count($languages) == 1) {
                $this->lockLocaleLanguage(reset($languages));
            }
        }

        return $this;
    }

    /**
     * @param $language
     * @return $this
     */
    public function lockLocaleLanguage($language)
    {
        $this->_language = $language;

        return $this;
    }

    /**
     * @return $this
     */
    public function unlockLocaleLanguage()
    {
        $this->_language = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLoadedLocale()
    {
        return !empty($this->_language);
    }

    /**
     * @param $language
     * @return bool
     */
    public function existsLocale($language)
    {
        return isset($this->locale[$language]);
    }

    /**
     * @param null $language
     * @return mixed
     * @throws \Exception
     */
    public function getLocale($language = null)
    {
        if (is_null($language) && isset($this->_language)) {
            $language = $this->_language;
        }

        if (count($this->locale) === 1) {
            reset($this->locale);
            $language = key($this->locale);
        }

        if (empty($language)) {
            if ($this->isArraying()) {
                return array();
            }

            throw new \Exception('Locale object could not be retrieved');
        }

        if (!isset($this->locale[$language])) {
            $this->locale[$language] = new $this->_locale(array($this->getId(), $language));
            $this->locale[$language]->setLanguage($language);
        }

        return $this->locale[$language];
    }

    /**
     * @return bool
     */
    public function hasLanguages()
    {
        return !empty($this->_languages);
    }

    /**
     * @param $languages
     * @return $this
     */
    public function setLanguages($languages)
    {
        $this->_languages = (array)$languages;

        return $this;
    }

    /**
     * @return array
     */
    public function getLanguages()
    {
        return $this->_languages;
    }

    /**
     * @param CommonEntity $locale
     * @param null $language
     * @return CommonEntity
     * @throws \Exception
     */
    public function setLocale(CommonEntity $locale, $language = null)
    {
        if (is_null($language) && isset($this->_language)) {
            $language = $this->_language;
        }

        if ($locale->hasLanguage()) {
            $language = $locale->getLanguage();
        }

        if (empty($language)) {
            throw new \Exception('Language cannot be empty');
        }

        $locale->setLanguage($language);
        $this->locale[$language] = $locale;
        $this->addRequirement('locale');

        return $this->getLocale($language);
    }
}
