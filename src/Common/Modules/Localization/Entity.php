<?php

namespace Common\Modules\Localization;

use Common\Modules\Entities\Entity as CommonEntity;

/**
 * Class Entity
 * @package Common\Modules\Localization
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
     * @var array
     */
    protected $locale = array();

    /**
     * @param array $parameters
     * @param array $languages
     * @return $this
     * @throws \Exception
     */
    public function load($parameters = array(), $languages = array())
    {
        parent::load($parameters);

        if (!empty($languages)) {
            foreach ($languages as $language) {
                if (isset($this->_locale)) {
                    /* @var $locale CommonEntity */
                    $locale = new $this->_locale(array($this->getId(), $language));
                    $this->setLocale($locale, $language);
                }
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
     * @param $language
     * @return Entity
     * @throws \Exception
     */
    public function getLocale($language = null)
    {
        if (is_null($language) && isset($this->_language)) {
            $language = $this->_language;
        }

        if (!isset($this->locale[$language])) {
            throw new \Exception('Locale does not exist');
        }

        return $this->locale[$language];
    }

    /**
     * @param null $language
     * @return CommonEntity
     * @throws \Exception
     */
    public function loadLocale($language = null)
    {
        return $this->setLocale(new $this->_locale(), $language);
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

        if (empty($language)) {
            throw new \Exception('Language cannot be empty');
        }

        $this->locale[$language] = $locale;

        return $this->getLocale($language);
    }
}
